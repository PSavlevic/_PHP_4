<?php

$form = [
    'action' => 'index.php',
    'method' => 'POST',
    'fields' => [
        'pirmas_skc' => [
            'type' => 'text',
            'placeholder' => 'ivesk pirma skc',
            'filter' => FILTER_SANITIZE_NUMBER_INT,
            'validators' => [
                'validate_is_number'
            ],
            'value' => '',
        ],
        'antras_skc' => [
            'type' => 'text',
            'filter' => FILTER_SANITIZE_NUMBER_INT,
            'placeholder' => 'ivesk antra skc',
            'validators' => [
                'validate_is_number'
            ],
            'value' => '',
        ],
    ],
    'callbacks' => [
        'success' => 'form_success',
        'fail' => 'form_fail'
    ]
];

function form_success($filtered_input, &$form) {
    $y = $filtered_input['pirmas_skc'] * $filtered_input['antras_skc'];
    print "Atsakymas: $y";
}

function form_fail($filtered_input, &$form) {
    print 'Nepavyko...';
}

function get_form_input($form)
{
    $filter_parameters = [];
    foreach ($form['fields'] as $field_id => $field) {
        if (isset($field['filter'])) {
            $filter_parameters[$field_id] = $field['filter'];
        }
    }
    return filter_input_array(INPUT_POST, $filter_parameters);
}

$filtered_input = get_form_input($form);
validate_form($filtered_input, $form);

function validate_form($filtered_input, &$form) {
    $success = true;
    foreach ($form['fields'] as $field_id => &$field) {
        $field['value'] = $filtered_input[$field_id];
        if (isset($field['validators'])) {
            foreach ($field['validators'] as $validator) {
                $is_valid = $validator($filtered_input[$field_id], $field);
                if (!$is_valid) {
                    $success = false;
                    break;
                }
            }
        }
    }
    if ($success) {
        if (isset($form['callbacks']['success'])) {
            $form['callbacks']['success']($filtered_input, $form);
        }
    } else {
        if (isset($form['callbacks']['fail'])) {
            $form['callbacks']['fail']($filtered_input, $form);
        }
    }
    return $success;
}


function validate_is_number($field_input, &$field)
{
    if (!is_numeric($field_input)) {
        $field['error'] = 'Įveskite skaičių!';
    } else {
        return true;
    }
}


?>
<html>
<head>
    <meta charset="UTF-8">
    <title>PHP form</title>
    <link rel="stylesheet" type="text/css" href="css/normalize.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<form action="<?php print $form['action']; ?>" method="<?php print $form['method']; ?>">
    <?php foreach ($form['fields'] as $fields_id => $fields): ?>
        <input type="<?php print $fields['type']; ?>"
               name="<?php print $fields_id; ?>"
               placeholder="<?php print $fields['placeholder']; ?>"
            <?php if (isset($fields['value'])): ?>
                value="<?php print $fields['value']; ?>"
            <?php endif; ?>
        >
        <?php if (isset($field['error'])): ?>
            <span class="error"><?php print $field['error']; ?></span>
        <?php endif; ?>
    <?php endforeach; ?>
    <button>Calculate</button>
</form>

</body>
</html>