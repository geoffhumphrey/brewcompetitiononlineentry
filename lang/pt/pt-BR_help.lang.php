<?php
$list_help_title = "Ajuda da Minha Conta";
$list_help_body = "<p>Esta é uma visualização abrangente das informações da sua conta.</p>";
$list_help_body .= "<p>Aqui você pode ver suas informações pessoais, incluindo nome, endereço, número(s) de telefone, clubes, número de membro da AHA, ID BJCP, classificação de juiz BJCP, preferências de avaliação e preferências de atendimento.</p>";
$list_help_body .= "<ul>";
$list_help_body .= "<li>Selecione o botão &ldquo;Editar Conta&rdquo; para atualizar suas informações pessoais.</li>";
$list_help_body .= "<li>Selecione o botão &ldquo;Alterar Email&rdquo; para atualizar seu endereço de e-mail. <strong>Observação:</strong> seu endereço de e-mail também é seu nome de usuário.</li>";
$list_help_body .= "<li>Selecione o botão &ldquo;Alterar Senha&rdquo; para atualizar a senha da sua conta.</li>";
$list_help_body .= "</ul>";
$list_help_body .= "<p>No final da página, está sua lista de inscrições.</p>";
$list_help_body .= "<ul>";
$list_help_body .= "<li>Selecione o ícone da impressora <span class=\"fa fa-print\"></span> para imprimir a documentação necessária para cada inscrição (rótulos de garrafas, etc.).</li>";
$list_help_body .= "<li>Selecione o ícone do lápis <span class=\"fa fa-pencil\"></span> para editar a inscrição.</li>";
$list_help_body .= "<li>Selecione o ícone da lixeira <span class=\"fa fa-trash-o\"></span> para excluir a inscrição.</li>";
$list_help_body .= "</ul>";

$brewer_acct_edit_help_title = "Ajuda para Editar a Conta";
$brewer_acct_edit_help_body = "<p>Aqui você pode atualizar as informações da sua conta, incluindo endereço/telefone, número de membro da AHA, ID BJCP, classificação de juiz BJCP, disponibilidade e preferências de avaliação ou atendimento, e muito mais.";

$username_help_title = "Ajuda para Alterar o Endereço de Email";
$username_help_body = "<p>Aqui você pode alterar seu endereço de e-mail.</p>";
$username_help_body .= "<p><strong>Por favor, observe:</strong> seu endereço de e-mail também serve como nome de usuário para acessar sua conta neste site.</p>";

$password_help_title = "Ajuda para Alterar a Senha";
$password_help_body = "<p>Aqui você pode alterar a senha de acesso a este site. Quanto mais segura, melhor - inclua caracteres especiais e/ou números.</p>";

$pay_help_title = "Ajuda para Pagar as Taxas de Inscrição";
$pay_help_body = "<p>Esta tela detalha suas inscrições não pagas e as taxas associadas. Se os organizadores da competição designaram um desconto para os participantes com um código, você pode inserir o código antes de pagar suas inscrições.</p>";
$pay_help_body .= "<p>Para ".$_SESSION['contestName'].", os métodos de pagamento aceitos são:</p>";
$pay_help_body .= "<ul>";
if ($_SESSION['prefsCash'] == "Y") $pay_help_body .= "<li><strong>Dinheiro.</strong> Coloque dinheiro em um envelope e anexe-o a uma de suas garrafas. Por favor, por consideração à equipe de organização, evite pagar com moedas.</li>";
if ($_SESSION['prefsCheck'] == "Y") $pay_help_body .= "<li><strong>Verificação.</strong> Faça um cheque em nome de ".$_SESSION['prefsCheckPayee']." pelo valor total das taxas de inscrição, coloque-o em um envelope e anexe-o a uma de suas garrafas. Seria extremamente útil para a equipe da competição se você listasse os números das suas inscrições na seção de observações do cheque.</li>";
if ($_SESSION['prefsPaypal'] == "Y") $pay_help_body .= "<li><strong>Cartão de Crédito/Débito via PayPal.</strong> Para pagar suas taxas de inscrição com um cartão de crédito ou débito, selecione o botão &ldquo;Pagar com PayPal&rdquo;. Não é necessário ter uma conta PayPal. Após efetuar o pagamento, certifique-se de clicar no link &ldquo;Voltar para...&rdquo; na tela de confirmação do PayPal. Isso garantirá que suas inscrições sejam marcadas como pagas para esta competição.</li>";
$pay_help_body .= "</ul>";
?>