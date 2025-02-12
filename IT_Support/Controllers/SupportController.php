<?php
require_once '../models/SupportTicket.php';

class SupportController {
    private $db;
    private $ticket;

    public function __construct($db) {
        $this->db = $db;
        $this->ticket = new SupportTicket($db);
    }

    public function createTicket($user_id, $subject, $description) {
        $this->ticket->user_id = $user_id;
        $this->ticket->subject = $subject;
        $this->ticket->description = $description;
        return $this->ticket->create();
    }

    public function viewTickets($user_id) {
        $this->ticket->user_id = $user_id;
        return $this->ticket->readAll();
    }

    public function viewTicketDetails($id) {
        $this->ticket->id = $id;
        return $this->ticket->readOne();
    }

    public function updateTicketStatus($id, $status) {
        $this->ticket->id = $id;
        return $this->ticket->updateStatus($status);
    }

    public function addTicketComment($id, $comment) {
        $this->ticket->id = $id;
        return $this->ticket->addComment($comment);
    }

    public function getTicketComments($id) {
        $this->ticket->id = $id;
        return $this->ticket->getComments();
    }
}