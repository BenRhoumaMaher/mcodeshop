<?php

namespace App\Classe;

class State
{
    public const STATE = [
        '3' => [
            'label' => 'being prepared',
            'mail_subject' => 'order is being prepared',
            'mail_template' => 'order_state_3.html'
        ],
        '4' => [
            'label' => 'shipped',
            'mail_subject' => 'order shipped',
            'mail_template' => 'order_state_4.html'
        ],
        '5' => [
            'label' => 'canceled',
            'mail_subject' => 'order canceled',
            'mail_template' => 'order_state_5.html'
        ],
    ];
}
