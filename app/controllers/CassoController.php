<?php
class CassoController extends Controller {
    private $orderModel;

    public function __construct() {
        $this->orderModel = $this->model('OrderModel');
    }

    public function webhook() {
        // Log request for debugging
        $log = date('Y-m-d H:i:s') . " - Method: " . $_SERVER['REQUEST_METHOD'] . "\n";
        file_put_contents(APPROOT . '/../casso_log.txt', $log, FILE_APPEND);

        // Allow GET for verification, but only process POST
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            echo json_encode(['status' => 'success', 'message' => 'Webhook endpoint is active']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
            return;
        }

        // Get headers
        $headers = getallheaders();
        $secureToken = $headers['Secure-Token'] ?? '';

        // Validate Secure Token
        if ($secureToken !== CASSO_WEBHOOK_TOKEN) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            return;
        }

        // Get request body
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!$data || !isset($data['data'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
            return;
        }

        $transactions = $data['data'];
        $processedCount = 0;

        foreach ($transactions as $transaction) {
            $description = $transaction['description'];
            $amount = $transaction['amount'];
            $tid = $transaction['id'];

            // 1. Check if transaction was already processed
            if ($this->orderModel->isTransactionProcessed($tid)) {
                continue;
            }

            // 2. Extract Order ID from description (e.g., DH123 or DH 123)
            preg_match('/DH\s*(\d+)/i', $description, $matches);
            
            if (isset($matches[1])) {
                $orderId = $matches[1];
                
                // Get order to check total
                $order = $this->orderModel->getOrderById($orderId);
                
                if ($order) {
                    // Check if order is already paid
                    if (strtolower($order['payment_status']) === 'paid') {
                         // Log as success since it's already done
                         $this->orderModel->logBankTransaction([
                            'transaction_id' => $tid,
                            'amount' => $amount,
                            'description' => $description,
                            'order_id' => $orderId,
                            'status' => 'Already Paid'
                        ]);
                        continue;
                    }

                    // 3. Verify Amount (Anti-cheat)
                    // We allow a small tolerance or exact match
                    if ($amount >= $order['total_amount']) {
                        // Update order payment status
                        $this->orderModel->updatePaymentStatus($orderId, 'Paid');
                        
                        // Log transaction as Success
                        $this->orderModel->logBankTransaction([
                            'transaction_id' => $tid,
                            'amount' => $amount,
                            'description' => $description,
                            'order_id' => $orderId,
                            'status' => 'Success'
                        ]);
                        $processedCount++;
                    } else {
                        // Log as insufficient amount
                        $this->orderModel->logBankTransaction([
                            'transaction_id' => $tid,
                            'amount' => $amount,
                            'description' => $description,
                            'order_id' => $orderId,
                            'status' => 'Insufficient Amount'
                        ]);
                    }
                } else {
                    // Log failed match
                    $this->orderModel->logBankTransaction([
                        'transaction_id' => $tid,
                        'amount' => $amount,
                        'description' => $description,
                        'order_id' => null,
                        'status' => 'Order Not Found'
                    ]);
                }
            } else {
                // Log irrelevant transaction
                $this->orderModel->logBankTransaction([
                    'transaction_id' => $tid,
                    'amount' => $amount,
                    'description' => $description,
                    'order_id' => null,
                    'status' => 'No Order Code'
                ]);
            }
        }

        echo json_encode(['status' => 'success', 'processed' => $processedCount]);
    }
}
