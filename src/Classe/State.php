<?php

/**
 * State
 *
 * This class contains constants representing different order states,
 * along with their labels and email templates.
 *
 * @category Classes
 * @package  App\Classe
 * @author   Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @license  No license (Personal project)
Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @link     https://symfony.com/doc/current/index.html
 */

namespace App\Classe;

/**
 * State
 *
 * Contains order states, labels, and associated email templates.
 *
 * @category Classes
 * @package  App\Classe
 */
class State
{
    public const STATE = [
        '3' => [
            'label' => 'being prepared',
            'mail_subject' => 'order is being prepared',
            'mail_template' => 'order_state_3.html',
        ],
        '4' => [
            'label' => 'shipped',
            'mail_subject' => 'order shipped',
            'mail_template' => 'order_state_4.html',
        ],
        '5' => [
            'label' => 'canceled',
            'mail_subject' => 'order canceled',
            'mail_template' => 'order_state_5.html',
        ],
    ];
}
