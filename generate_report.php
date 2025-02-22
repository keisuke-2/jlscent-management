<?php
ob_start();

require_once 'vendor/autoload.php'; 
include 'db.php';

$pdf = new TCPDF();
$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(200, 10, 'Admin Dashboard Report', 0, 1, 'C');

$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 12);

try {
    $stmt = $pdo->query("SELECT 
                            (SELECT SUM(amount) FROM revenue) AS total_revenue,
                            (SELECT COUNT(*) FROM orders) AS total_orders");
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_revenue = $data['total_revenue'] ?? 0;
    $total_orders = $data['total_orders'] ?? 0;
} catch (PDOException $e) {
    die('Database query failed: ' . $e->getMessage());
}

$pdf->SetFont('dejavusans', '', 12);

$pdf->Cell(100, 10, 'Total Revenue:', 0, 0);
$pdf->Cell(100, 10, '₱' . number_format($total_revenue, 2), 0, 1);

$pdf->Ln(5); 

$pdf->Cell(100, 10, 'Total Orders:', 0, 0);
$pdf->Cell(100, 10, $total_orders, 0, 1);

$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(200, 10, 'Order Details:', 0, 1);

try {
    $stmt = $pdo->query("SELECT o.order_id, o.status, o.total_amount, oi.product_id, p.name, oi.quantity, oi.price
                         FROM orders o
                         JOIN order_items oi ON o.order_id = oi.order_id
                         JOIN products p ON oi.product_id = p.product_id");
} catch (PDOException $e) {
    die('Database query failed: ' . $e->getMessage());
}

$pdf->SetFont('dejavusans', '', 10);

$pdf->Cell(30, 10, 'Order ID', 1);
$pdf->Cell(30, 10, 'Status', 1);
$pdf->Cell(40, 10, 'Product', 1);
$pdf->Cell(30, 10, 'Quantity', 1);
$pdf->Cell(30, 10, 'Price', 1);
$pdf->Ln();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $pdf->Cell(30, 10, $row['order_id'], 1);
    $pdf->Cell(30, 10, $row['status'], 1);
    $pdf->Cell(40, 10, $row['name'], 1);
    $pdf->Cell(30, 10, $row['quantity'], 1);
    $pdf->Cell(30, 10, '₱' . number_format($row['price'], 2), 1);
    $pdf->Ln();
}

ob_end_clean();
$pdf->Output();
?>
