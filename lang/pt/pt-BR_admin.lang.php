<?php
/**
 * Module:      en-US_admin.lang.php
 * Description: This module houses all display text in the English language.
 *
 */

/*

--------------------------------------------------------------------------------------------------

To translate this file, first make a copy of it and rename it with the language name in the title.

==============================

Use ISO 169-2 Standards for and WWW3C Language Tag Standards for naming of language files. Use the
ALPHA-2 letter code whenever possible.

ISO 169-2:
https://www.loc.gov/standards/iso639-2/php/code_list.php

WWW3 Language Tags:
https://www.w3.org/International/articles/language-tags/

WWW3 Choosing a Language Tag:
https://www.w3.org/International/questions/qa-choosing-language-tags

According to the WWW3:

"Always bear in mind that the golden rule is to keep your language tag as short as possible. Only
add further subtags to your language tag *if they are needed to distinguish the language from
something else in the context where your content is used...*

"Unless you specifically need to highlight that you are talking about Italian as spoken in Italy
you should use it for Italian, and not it-IT. The same goes for any other possible combination."

To determine a subtag, go to the IANA Language Subtag Registry:
http://www.iana.org/assignments/language-subtag-registry

==============================

Items that need translation into other languages are housed here in PHP variables - each start with
a dollar sign ($). The words, phrases, etc. (called strings) that need to be translated are housed
between double-quotes ("). Please, ONLY alter the text between the double quotes!

For example, a translated PHP variable would look like this:

English (US) before translation:
$label_volunteer_info = "Volunteer Info";

Spanish translated:
$label_volunteer_info = "Información de Voluntarios";

Portuguese translated:
$label_volunteer_info = "Informações Voluntário";

==============================

Please note: the strings that need to be translated MAY contain HTML code. Please leave this code
intact! For example:

English (US):
$beerxml_text_008 = "Browse for your BeerXML compliant file on your hard drive and click <em>Upload</em>.";

Spanish:
$beerxml_text_008 = "Buscar su archivo compatible BeerXML en su disco duro y haga clic en <em>Cargar</em>.";

Note that the <em>...</em> tags were not altered. Just the word "Upload" to "Cargar" betewen those tags.

==============================

*/

// -------------------- Archive --------------------

$archive_text_000 = "Devido a limitações de armazenamento do servidor, o arquivamento de dados da conta hospedada não está disponível. Para utilizar o software para uma nova competição ou simplesmente limpar o banco de dados de dados, use os botões abaixo.";
$archive_text_001 = "Categoria personalizada, tipo de estilo personalizado, local de entrega, local de julgamento e dados do patrocinador <strong class=\"text-success\"> não serão eliminados </strong>. Os administradores precisarão atualizá-los para futuras instâncias da concorrência.";
$archive_text_002 = "Opção 1";
$archive_text_003 = "Tem certeza de que deseja limpar os dados da competição atual? Isto não pode ser desfeito.";
$archive_text_004 = "Limpar todos os dados de participante, entrada, julgamento e pontuação";
$archive_text_005 = "Essa opção limpa todas as contas de participantes que não são administradores, bem como todos os dados de entrada, julgamento e pontuação, incluindo todas as planilhas enviadas. Fornece uma ardósia limpa.";
$archive_text_006 = "Opção 2";
$archive_text_007 = "";
$archive_text_008 = "Somente dados claros de entrada, julgamento e pontuação";
$archive_text_009 = "Essa opção limpa todos os dados de entrada, julgamento e pontuação, incluindo todas as planilhas carregadas, mas mantém os dados do participante. Útil se você não quiser que os participantes criem novos perfis de conta.";
$archive_text_010 = "Para arquivar dados atualmente armazenados no banco de dados, forneça um nome para o arquivo morto.";
$archive_text_011 = "Apenas caracteres alfanuméricos - todos os outros serão omitidos.";
$archive_text_012 = "Verifique as informações que você deseja reter para uso em futuras instâncias da concorrência.";
$archive_text_013 = "Tem certeza de que deseja arquivar dados atuais?";
$archive_text_014 = "Em seguida, escolha quais dados você gostaria de reter.";
$archive_text_015 = "Isso excluirá o arquivo chamado";
$archive_text_016 = "Todos os registros associados serão excluídos.";

/*
 * --------------------- v 2.2.0 -----------------------
 */
$archive_text_017 = "Edite as informações do arquivo com cuidado. Alterar qualquer um dos itens a seguir pode resultar em um comportamento inesperado ao tentar acessar os dados arquivados.";
$archive_text_018 = "Os arquivos serão movidos para uma subpasta com o mesmo nome do seu arquivo no diretório user_docs.";
$archive_text_019 = "Lista (s) de vencedores arquivados disponíveis para exibição pública.";
$archive_text_020 = "Geralmente, isso só deve ser alterado se a lista de vencedores deste arquivo for exibida incorretamente.";
$archive_text_021 = "Folhas de pontuação em PDF foram salvas para este arquivo. Esta é a convenção de nomenclatura de cada arquivo usado pelo sistema ao acessá-los.";
$archive_text_022 = "Desativado. Não existem dados de resultados para este arquivo.";

$label_uploaded_scoresheets = "Folhas de Pontuação Carregadas (Arquivos PDF)";
$label_admin_comp_type = "Tipo de Competição";
$label_admin_styleset = "Conjunto de Estilos";
$label_admin_winner_display = "Exibição do Vencedor";
$label_admin_enable = "Permitir";
$label_admin_disable = "Desabilitar";
$label_admin_winner_dist = "Método de Distribuição do Lugar do Vencedor";
$label_admin_archive = "Arquivo";
$label_admin_scoresheet_names = "Nomes de Arquivos de Upload de Planilhas";
$label_six_char_judging = "Número de Julgamento de 6 Caracteres";
$label_six_digit_entry = "Número de Entrada de 6 Dígitos";
$label_not_archived = "Não Arquivado";

// -------------------- Barcode Check-In --------------------



// -------------------- Navigation --------------------

?>