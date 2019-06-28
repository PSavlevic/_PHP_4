<?php

$form = [
    'action' => 'index.php',
    'method' => 'POST',
    'fields' => [
        'vardas' => [
            'type' => 'text',
            'label' => "Name:",
            'value' => '',
            'placeholder' => 'Vardas',
            'validators' => [
                'validate_not_empty'
            ],
            'id' => 'vardenis',
        ],
        'email' => [
            'type' => 'email',
            'label' => "Email:",
            'value' => '',
            'validators' => [
                'validate_not_empty'
            ],
            'placeholder' => 'Email',
            'id' => 'emailiukas'
        ],
        'passwordas1' => [
            'type' => 'password',
            'label' => "Password:",
            'value' => '',
            'validators' => [
                'validate_not_empty',
                'validate_length',
            ],
            'placeholder' => 'Password',
            'id' => 'paswordas1'
        ],
        'passwordas2' => [
            'type' => 'password',
            'label' => "Password repeat:",
            'value' => '',
            'validators' => [
                'validate_not_empty',
                'validate_length',
            ],
            'placeholder' => 'Password dar karta',
            'id' => 'paswordas2'
        ],
    ]
];

function get_form_input($form)
{
    $filtered = [];
    foreach ($form['fields'] as $field_id => $field) {
        $filtered[$field_id] = FILTER_SANITIZE_SPECIAL_CHARS;
    }
    return filter_input_array(INPUT_POST, $filtered);
}

$safe_input = get_form_input($form);
validate_form($safe_input, $form);

function validate_form($field_input, &$form)
{
    $success = true;
    foreach ($form['fields'] as $field_id => &$field) {
        if (isset($field['validators'])) {
            foreach ($field['validators'] as $validator) {
                $is_valid = $validator($field_input[$field_id], $field);
                if (!$is_valid) {
                    $success = false;
                    break;
                }
            }
        }
    }
}

//validate_not_empty($safe_input['vardas'], $form['fields']['vardas']);
//validate_not_empty($safe_input['email'], $form['fields']['email']);
//validate_not_empty($safe_input['passwordas1'], $form['fields']['passwordas1']);
//validate_not_empty($safe_input['passwordas2'], $form['fields']['passwordas2']);

function validate_not_empty($field_input, &$field)
{
    if (strlen($field_input) == 0) {
        $field['error'] = 'tuscias laukelis';
    } else {
        return true;
    }
}

function validate_length($field_input, &$field)
{
    if (strlen($field_input) < 8) {
        $field['error'] = 'iveskite maziausiai 8 simbolius!';
    } else {
        return true;
    }
}

//if (isset($safe_input)){
//sutapimas($safe_input['passwordas1'], $safe_input['passwordas2'], $form);
//}

//function sutapimas($pass1, $pass2, &$field) {
//    if($pass1 != $pass2){
//        $field['error'] = 'Slaptazodziai nesutampa!!';
//    } else {
//        return true;
//    }
//}

//Duomenu irasymas i info.csv faila!!!!
if (isset($_POST['mygtukas'])) {
//    SUKURIAMAS KINTAMASIS APJUNGIANTIS VISUS POST LAUKELIUS
    $stringData = $safe_input['vardas'] . '  ' . $safe_input['email'] . ' ' . $safe_input['passwordas1'] . ' ' . $safe_input['passwordas2'] . "\n";
//    NURODOMA .csvFAILO VIETA IR KAS BUS JAME DAROMA
    $csvFile = "info.csv";
    $fh = fopen($csvFile, 'a') or die("can't open file");
//    IRASOMI DUOMENYS I NURODYTA .csv FAILA
    fwrite($fh, $stringData);
//    UZDAROMAS FAILAS
    fclose($fh);
    header("Location:index.php");
}

var_dump($_POST);
var_dump(get_form_input($form));

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="forma">
    <h1>Registracijos forma</h1>
    <form action="<?php print $form['action']; ?>" method="<?php print $form['method']; ?>">
        <?php foreach ($form['fields'] as $field_id => $field): ?>
            <label for="<?php print $field['id']; ?>"><?php print $field['label']; ?></label>
            <input type="text" name="<?php print $field_id; ?>" placeholder="<?php print $field['placeholder']; ?>"
                   id="<?php print $field['id']; ?>" value=""
            >
            <?php if (isset($field['error'])): ?>
                <span class="error"><?php print $field['error']; ?></span>
            <?php endif; ?>
        <?php endforeach; ?>
        <button name="mygtukas">Pateikti</button>
    </form>
<!--    antras-->
</div>
</body>
</html>