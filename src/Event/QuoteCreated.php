<?php
namespace App\Event;

use App\Entity\Quote;
use Symfony\Contracts\EventDispatcher\Event;

class QuoteCreated extends Event
{
    protected $order;

    public function __construct(Quote $quote)
    {
        $this->quote = $quote;
    }

    public function getOrder()
    {
        return $this->order;
    }
}