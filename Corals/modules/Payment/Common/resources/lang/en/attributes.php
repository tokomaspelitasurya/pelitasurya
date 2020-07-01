<?php

return [
    'invoice' => [
        'invoicable_type' => 'Type',
        'invoicable_id' => 'Details',
        'user_id' => 'User',
        'customer' => 'Customer',
        'total' => 'Total',
        'sub_total' => 'Sub Total',
        'description' => 'Description',
        'terms' => 'Terms',
        'code' => 'Code',
        'is_sent' => 'Is Sent?',
        'due_date' => 'Due Date',
        'invoice_date' => 'Invoice Date',
        'invoice_code' => 'Invoice Number',
        'status' => 'Status',
        'invoice_option' => [
            'paid' => 'Paid',
            'failed' => 'Failed',
            'pending' => 'Pending'
        ],
        'currency' => 'Currency',
    ],
    'tax_class' => [
        'name' => 'Name',
        'taxes' => 'Taxes'
    ],
    'tax' => [
        'name' => 'Name',
        'country' => 'Country',
        'state' => 'State',
        'zip' => 'Zip',
        'rate' => 'Rate',
        'priority' => 'Priority',
        'compound' => 'Compound',
    ],
    'webhook_call' => [
        'event_name' => 'Event',
        'payload' => 'Payload',
        'exception' => 'Exception',
        'gateway' => 'Gateway',
        'processed' => 'Processed',
        'process' => 'Process',
    ],
    'currency' => [
        'name' => 'Name',
        'code' => 'Code',
        'symbol' => 'Symbol',
        'format' => 'Format',
        'exchange_rate' => 'Exchange rate',
    ],
    'transaction' => [
        'invoice' => 'Invoice',
        'type' => 'Type',
        'source' => 'Source',
        'amount' => 'Amount',
        'status' => 'Status',
        'currency' => 'Currency',
        'notes' => 'Notes',
        'reference' => 'Reference',
        'paid_amount' => 'Paid Amount',
        'types' => [
            'order_revenue' => 'Order Sale',
            'commision' => 'Commision',
            'withdrawal' => 'Withdrawal',
            'shipping' => 'Shipping',
            'order_refund' => 'Refund Order'
        ]

    ],

    'update_status' => 'Updated Status !',
    'no_permission' => 'There is no permission update status'
];
