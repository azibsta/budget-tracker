<?php

class SupportTicket {
    private $conn;
    private $table = 'support_tickets';

    public $id;
    public $user_id;
    public $subject;
    public $description;
    public $status;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " (user_id, subject, description, status, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->user_id, $this->subject, $this->description, 'open']);
        return $stmt;
    }

    public function readAll() {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStatus($status) {
        $query = "UPDATE " . $this->table . " SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$status, $this->id]);
    }

    public function addComment($comment) {
        $query = "INSERT INTO support_comments (ticket_id, comment, created_at) VALUES (?, ?, NOW())";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$this->id, $comment]);
    }

    public function getComments() {
        $query = "SELECT * FROM support_comments WHERE ticket_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}