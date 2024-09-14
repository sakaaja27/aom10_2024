<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $table = "ticket";
    protected $primaryKey = "idTicket";
    protected $fillable = [
        "idTicket",
        "name",
        "price",
        "quantity"
    ];
    public function ticket_benefit(){
        return $this->hasMany(ticket_benefits::class,'id_ticket','idTicket');
    }
}
