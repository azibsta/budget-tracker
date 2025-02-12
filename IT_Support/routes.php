<?php
require_once 'controllers/SupportController.php';
require_once '../../config/db.php';

$controller = new SupportController($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create_ticket'])) {
        $user_id = $_SESSION['user_id'];
        $subject = $_POST['subject'];
        $description = $_POST['description'];
        $controller->createTicket($user_id, $subject, $description);
        header("Location: view_tickets.php");
        exit();
    } elseif (isset($_POST['update_status'])) {
        $ticket_id = $_POST['ticket_id'];
        $status = $_POST['status'];
        $controller->updateTicketStatus($ticket_id, $status);
        header("Location: ticket_details.php?ticket_id=$ticket_id");
        exit();
    } elseif (isset($_POST['add_comment'])) {
        $ticket_id = $_POST['ticket_id'];
        $comment = $_POST['comment'];
        $controller->addTicketComment($ticket_id, $comment);
        header("Location: ticket_details.php?ticket_id=$ticket_id");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['ticket_id'])) {
    $ticket_id = $_GET['ticket_id'];
    $ticket = $controller->viewTicketDetails($ticket_id);
    $comments = $controller->getTicketComments($ticket_id);
    include 'views/ticket_details.php';
    exit();
}

$tickets = $controller->viewTickets($_SESSION['user_id']);
include 'views/view_tickets.php';