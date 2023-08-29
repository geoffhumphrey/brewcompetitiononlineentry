<?php
/**
 * Module:      pt-BR.lang.php
 * Description: This module houses all display text in the Portuguese language.
 * Updated:     January 17, 2023
 *  
 * To translate this file, first make a copy of it and rename it with the 
 * language name in the title.
 * 
 * ==============================
 * 
 * Use ISO 169-2 Standards for and WWW3C Language Tag Standards for naming 
 * of language files. Use the ALPHA-2 letter code whenever possible.
 * 
 * ISO 169-2:
 * @see https://www.loc.gov/standards/iso639-2/php/code_list.php
 * 
 * WWW3 Language Tags:
 * @see https://www.w3.org/International/articles/language-tags/
 * 
 * WWW3 Choosing a Language Tag:
 * @see https://www.w3.org/International/questions/qa-choosing-language-tags
 * 
 * To determine a subtag, go to the IANA Language Subtag Registry:
 * @see http://www.iana.org/assignments/language-subtag-registry
 * 
 * According to the WWW3:
 * 
 * "Always bear in mind that the golden rule is to keep your language tag 
 * as short as possible. Only add further subtags to your language tag if 
 * they are needed to distinguish the language from something else in the 
 * context where your content is used..."
 * 
 * "Unless you specifically need to highlight that you are talking about 
 * Italian as spoken in Italy you should use it 'for Italian, and not 
 * it-IT. The same goes for any other possible combination."
 * 
 * "You should only use a region subtag if it contributes information 
 * needed in a particular context to distinguish this language tag from 
 * another one; otherwise leave it out."
 * 
 * ================ FORMAT =================
 * 
 * Always indicate the primary language subtag first, then a dash (-)
 * and then the region subtag. The region subtag is in all capital letters 
 * or a three digit number.
 * 
 * Examples:
 * en-US
 * English spoken in the United States
 * en is the PRIMARY language subtag
 * US is the REGION subtag (note the capitalization)
 * 
 * es-ES
 * Spanish spoken in Spain
 * 
 * es-419
 * Spanish spoken in Latin America
 * 
 * ========================================
 * 
 * Items that need translation into other languages are housed here in 
 * PHP variables - each start with a dollar sign ($). The words, phrases,
 * etc. (called strings) that need to be translated are housed between 
 * double-quotes ("). Please, ONLY alter the text between the double quotes!
 * 
 * For example, a translated PHP variable would look like this (encoding is utf8mb4; therefore, 
 * accented and other special characters are acceptable):
 * 
 * English (US) before translation:
 * $label_volunteer_info = "Volunteer Info";
 * 
 * Spanish translated:
 * $label_volunteer_info = "Información de Voluntarios";
 * 
 * Portuguese translated:
 * $label_volunteer_info = "Informações Voluntário";
 * 
 * ========================================
 * 
 * Please note: the strings that need to be translated MAY contain HTML 
 * code. Please leave this code intact! For example:
 * 
 * English (US):
 * $beerxml_text_008 = "Browse for your BeerXML compliant file on your hard drive and select <em>Upload</em>.";
 * 
 * Spanish:
 * $beerxml_text_008 = "Buscar su archivo compatible BeerXML en su disco duro y haga clic en <em>Cargar</em>.";
 * 
 * Note that the <em>...</em> tags were not altered. Just the word "Upload" 
 * to "Cargar" between those tags.
 * 
 * ==============================
 * 
 */

/**
 * Set up PHP variables. No translations in this section.
 */

include (INCLUDES.'url_variables.inc.php');

if (isset($entry_open)) $entry_open = $entry_open;
else $entry_open = "";

if (isset($judge_open)) $judge_open = $judge_open;
else $judge_open = "";

if (isset($judge_closed)) $judge_closed = $judge_closed;
else $judge_closed = "";

if (isset($reg_open)) $reg_open = $reg_open;
else $reg_open = "";

if (isset($total_entries)) $total_entries = $total_entries;
else $total_entries = "";

if (isset($current_time)) $current_time = $current_time;
else $current_time = "";

if (isset($current_time)) $current_time = $current_time;
else $current_time = "";

if (isset($row_limits['prefsEntryLimit'])) $row_limits['prefsEntryLimit'] = $row_limits['prefsEntryLimit'];
else $row_limits['prefsEntryLimit'] = "";

if (isset($row_limits['prefsEntryLimitPaid'])) $row_limits['prefsEntryLimitPaid'] = $row_limits['prefsEntryLimitPaid'];
else $row_limits['prefsEntryLimitPaid'] = "";

$php_version = phpversion();

/**
 * ------------------------------------------------------------------------
 * BEGIN TRANSLATIONS BELOW!
 * ------------------------------------------------------------------------
 */

$j_s_text = "";
if (strpos($section, "step") === FALSE) {
    if ((isset($judge_limit)) && (isset($steward_limit))) {
        if (($judge_limit) && (!$steward_limit)) $j_s_text = "Steward"; // missing punctuation intentional
        elseif ((!$judge_limit) && ($steward_limit)) $j_s_text = "Judge"; // missing punctuation intentional
        else $j_s_text = "Judge or steward"; // missing punctuation intentional
    }
}

$label_home = "Home";
$label_welcome = "Benvindo";
$label_comps = "Diretório da Competição";
$label_info = "Info";
$label_volunteers = "Voluntários";
$label_register = "Registrar";
$label_help = "Ajuda";
$label_print = "Imprimir";
$label_my_account = "Minha Conta";
$label_yes = "Sim";
$label_no = "Não";
$label_low_none = "Baixo/Nenhum";
$label_low = "Baixo";
$label_med = "Médio";
$label_high = "Alto";
$label_pay = "Pagar Inscrição da Amostra";
$label_reset_password = "Resetar Password";
$label_log_in = "Log In";
$label_logged_in = "Logado";
$label_log_out = "Log Out";
$label_logged_out = "Deslogado";
$label_sponsors = "Patrocinadores";
$label_rules = "Regras";
$label_volunteer_info = "Informações de Voluntários";
$label_reg = $label_register;
$label_judge_reg = "Registro de Juiz";
$label_steward_reg = "Registro de Auxiliares";
$label_past_winners = "Vencedores Anteriores";
$label_contact = "Contato";
$label_style = "Estilo";
$label_entry = "Amostra";
$label_add_entry = "Adicionar Amostra";
$label_edit_entry = "Editar Amostra";
$label_upload = "Upload";
$label_bos = "Best of Show";
$label_brewer = "Cervejeiro";
$label_cobrewer = "Co-Cervejeiro";
$label_entry_name = "Nome da Amostra";
$label_required_info = "Informação Necessária";
$label_character_limit = " limite de caracteres - use palavras-chaves e abreviações se o espaço for limitado.<br>Characteres usados: ";
$label_carbonation = "Carbonatação";
$label_sweetness = "Dulçor";
$label_strength = "Força";
$label_color =  "Cor";
$label_table = "Mesa";
$label_standard = "Standard";
$label_super = "Super";
$label_session = "Session";
$label_double = "Double";
$label_blonde = "Blonde";
$label_amber = "Amber";
$label_brown = "Brown";
$label_pale = "Pale";
$label_dark = "Dark";
$label_hydromel = "Hidromel";
$label_sack = "Sack";
$label_still = "Still";
$label_petillant = "Petillant";
$label_sparkling = "Sparkling";
$label_dry = "Dry";
$label_med_dry = "Medium Dry";
$label_med_sweet = "Medium Sweet";
$label_sweet = "Sweet";
$label_brewer_specifics = "Especificidades do Cervejeiro";
$label_general = "Geral";
$label_amount_brewed = "Quantidade Brassada";
$label_specific_gravity = "Gravidade Específica";
$label_fermentables = "Fermentáveis";
$label_malt_extract = "Extrato de Malte";
$label_grain = "Grãos";
$label_hops = "Lúpulos";
$label_hop = "Lúpulo";
$label_mash = "Mash";
$label_steep = "Steep";
$label_other = "Outro";
$label_weight = "Peso";
$label_use = "Uso";
$label_time = "Tempo";
$label_first_wort = "First Wort";
$label_boil = "Fervura";
$label_aroma = "Aroma";
$label_dry_hop = "Dry Hop";
$label_type = "Tipo";
$label_bittering = "Bittering";
$label_both = "Ambos";
$label_form = "Forma";
$label_whole = "Flor";
$label_pellets = "Pellets";
$label_plug = "Plug";
$label_extract = "Extrato";
$label_date = "Data";
$label_bottled = "Engarrafado";
$label_misc = "Miscellaneous";
$label_minutes = "Minutos";
$label_hours = "Horas";
$label_step = "Passos";
$label_temperature = "Temperatura";
$label_water = "Água";
$label_amount = "Quantidade";
$label_yeast = "Fermento";
$label_name = "Nome";
$label_manufacturer = "Fabricante";
$label_nutrients = "Nutrientes";
$label_liquid = "Líquido";
$label_ale = "Ale";
$label_lager = "Lager";
$label_wine = "Wine";
$label_champagne = "Champagne";
$label_boil = "Fervura";
$label_fermentation = "Fermentação";
$label_finishing = "Finishing";
$label_finings = "Finings";
$label_primary = "Primary";
$label_secondary = "Secondary";
$label_days = "Dias";
$label_forced = "Carbonatação Forçada";
$label_bottle_cond = "Refermentação na garrafa";
$label_volume = "Volume";
$label_og = "Gravidade Original";
$label_fg = "Gravidade Final";
$label_starter = "Starter";
$label_password = "Senha";
$label_judging_number = "Número para Julgamento";
$label_check_in = "Check In da Amostra";
$label_box_number = "Número da Caixa";
$label_first_name = "Nome";
$label_last_name = "Sobrenome";
$label_secret_01 = "Qual é a sua cerveja favorita de todos os tempos?";
$label_secret_02 = "Qual foi o nome do seu primeiro animal de estimação?";
$label_secret_03 = "Qual era o nome da rua em que você cresceu?";
$label_secret_04 = "Qual era o nome do bairro em que você nasceu?";
$label_security_answer = "Resposta da Pergunta de Segurança";
$label_security_question = "Pergunta de segurança";
$label_judging = "Julgando";
$label_judge = "Juiz";
$label_steward = "Auxiliar";
$label_account_info = "Detalhes da Conta";
$label_street_address = "Endereço - Rua";
$label_address = "Endereço";
$label_city = "Cidade";
$label_state_province = "Estado";
$label_zip = "CEP";
$label_country = "País";
$label_phone = "Telefone";
$label_phone_primary = "Telefone Primário";
$label_phone_secondary = "Telefone Secundário";
$label_drop_off = "Local para entrega presencial de";
$label_drop_offs = "Locais para entrega presencial de";
$label_club = "Clube";
$label_aha_number = "AHA Member Number";
$label_org_notes = "Observações para os Organizadores";
$label_avail = "Disponibilidade";
$label_location = "Local";
$label_judging_avail = "Disponibilidade para Sessão de Julgamento";
$label_stewarding = "Stewarding";
$label_stewarding_avail = "Disponibilidade para Auxiliar a Sessão de Julgamento";
$label_bjcp_id = "BJCP ID";
$label_bjcp_mead = "Juiz de Hidromel";
$label_bjcp_rank = "BJCP Rank";
$label_designations = "Designações";
$label_judge_sensory = "Juiz com Treinamento Sensorial";
$label_judge_pro = "Cervejeiro Profissional";
$label_judge_comps = "Competições Julgadas";
$label_judge_preferred = "Estilos Preferidos";
$label_judge_non_preferred = "Estilos Não-Preferidos";
$label_waiver = "Waiver";
$label_add_admin = "Adicionar Detalhes do Administrador";
$label_add_account = "Adicionar Detalhes do Usuário";
$label_edit_account = "Editar Detalhes do Usuário";
$label_entries = "Amostras";
$label_confirmed = "Confirmadas";
$label_paid = "Pagas";
$label_updated = "Atualizadas";
$label_mini_bos = "Mini-BOS";
$label_actions = "Ações";
$label_score = "Pontuação";
$label_winner = "Vencedor?";
$label_change_email = "Alterar Email";
$label_change_password = "Alterar Senha";
$label_add_beerXML = "Adicionar Amostra Usando BeerXML";
$label_none_entered = "Nenhuma entrada";
$label_none = "Nenhuma";
$label_discount = "Desconto";
$label_subject = "Assunto";
$label_message = "Mensagem";
$label_send_message = "Enviar Mensagem";
$label_email = "Endereço de Email";
$label_account_registration = "Registro de usuário";
$label_entry_registration = "Inscrição de amostra";
$label_entry_fees = "Taxas das Amostras";
$label_entry_limit = "Limite de Amostras";
$label_entry_info = "Detalhes da Amostra";
$label_entry_per_entrant = "Limite de Amostras por Participante";
$label_categories_accepted = "Estilos Aceitos";
$label_judging_categories = "Categorias de Julgamento";
$label_entry_acceptance_rules = "Regras para Aceitação da Amostra";
$label_shipping_info = "Detalhes do Envio";
$label_packing_shipping = "Embalando e Enviando";
$label_awards = "Premiação";
$label_awards_ceremony = "Cerimônia de Premiação";
$label_circuit = "Qualificação do Circuito";
$label_hosted = "Hosted Edition";
$label_entry_check_in = "Check-In de Amostra";
$label_cash = "Dinheiro";
$label_check = "Cheque";
$label_pay_online = "Pagamento Online";
$label_cancel = "Cancelar";
$label_understand = "Eu entendi";
$label_fee_discount = "Desconto da taxa da Amostra";
$label_discount_code = "Código de Desconto";
$label_register_judge = "Você está se registrando como Participante, Juiz ou Auxiliar?";
$label_register_judge_standard = "Registrar como Juiz ou Auxiliar (Standard)";
$label_register_judge_quick = "Registrar como Juiz ou Auxiliar (Quick)";
$label_all_participants = "Todos os Participantes";
$label_open = "Aberto";
$label_closed = "Fechado";
$label_judging_loc = "Locais e Datas das Sessões de Julgamento";
$label_new = "Novo";
$label_old = "Antigo";
$label_sure = "Você tem certeza?";
$label_judges = "Juízes";
$label_stewards = "Auxiliar";
$label_staff = "Coordenação";
$label_category = "Categoria";
$label_delete = "Apagar";
$label_undone = "Esta ação não pode ser desfeita";
$label_bitterness = "Amargor";
$label_close = "Fechar";
$label_custom_style = "Estilo Personalizado";
$label_custom_style_types = "Tipos de Estilos Personalizados";
$label_assigned_to_table = "Atribuída à Mesa";
$label_organizer = "Organizador";
$label_by_table = "Por Mesa";
$label_by_category = "Por Estilo";
$label_by_subcategory = "Por Sub-Estilo";
$label_by_last_name = "Pelo Sobrenome";
$label_by_table = "Por Mesa";
$label_by_location = "Pela Localidade da Sessão";
$label_shipping_entries = "Envio das Amostras"; //
$label_no_availability = "Sem Disponibilidade Definida";
$label_error = "Erro";
$label_round = "Rodada";
$label_flight = "Flight";
$label_rounds = "Rodadas";
$label_flights = "Flights";
$label_sign_in = "Sign In";
$label_signature = "Assinatura";
$label_assignment = "Tarefas";
$label_assignments = "Tarefas";
$label_letter = "Carta";
$label_re_enter = "Re-Entrar";
$label_website = "Website";
$label_place = "Local";
$label_cheers = "Cheers";
$label_count = "Contagem";
$label_total = "Total";
$label_participant = "Participante";
$label_entrant = "Competidor";
$label_received = "Recebido";
$label_please_note = "Preste Atenção";
$label_pull_order = "Pull Order";
$label_box = "Caixa";
$label_sorted = "Ordenado";
$label_subcategory = "Subcategoria";
$label_affixed = "Etiqueta Afixada?";
$label_points = "Pontos";
$label_comp_id = "BJCP Competition ID";
$label_days = "Dias";
$label_sessions = "Sessões";
$label_number = "Número";
$label_more_info = "Mais Detalhes";
$label_entry_instructions = "Instruções da Amostra";
$label_commercial_examples = "Exemplos Comerciais";
$label_users = "Usuários";
$label_participants = "Participantes";
$label_please_confirm = "Favor Confirmar";
$label_undone = "Esta ação não pode ser desfeita.";
$label_data_retain = "Dados para Reter";
$label_comp_portal = "Diretório da Concurso";
$label_comp = "Concurso";
$label_continue = "Continuar";
$label_host = "Host";
$label_closing_soon = "Termina em Breve";
$label_access = "Accesso";
$label_length = "Comprimento";

$label_admin = "Administração";
$label_admin_short = "Admin";
$label_admin_dashboard = "Dashboard";
$label_admin_judging = $label_judging;
$label_admin_stewarding = "Auxiliando";
$label_admin_participants = $label_participants;
$label_admin_entries = $label_entries;
$label_admin_comp_info = "Detalhes do Concurso";
$label_admin_web_prefs = "Preferências do Website";
$label_admin_judge_prefs = "Preferências da Organização do Concurso";
$label_admin_archives = "Arquivos";
$label_admin_style = $label_style;
$label_admin_styles = "Estilos";
$label_admin_dropoff = $label_drop_offs;
$label_admin_judging_loc = $label_judging_loc;
$label_admin_contacts = "Contatos";
$label_admin_tables = "Mesas";
$label_admin_scores = "Scores";
$label_admin_bos = $label_bos;
$label_admin_bos_acr = "BOS";
$label_admin_style_types = "Tipos de Estilo";
$label_admin_custom_cat = "Categorias Personalizadas";
$label_admin_custom_cat_data = "Amostras da Categoria Personalizada";
$label_admin_sponsors = $label_sponsors;
$label_admin_entry_count = "Contagem de Amostras por Estilo";
$label_admin_entry_count_sub = "Contagem de Amostras por Sub-Estilo";
$label_admin_custom_mods = "Custom Modules";
$label_admin_check_in = $label_entry_check_in;
$label_admin_make_admin = "Alterar User Level";
$label_admin_register = "Registrar um Participante";
$label_admin_upload_img = "Upload Images";
$label_admin_upload_doc = "Upload Scoresheets e outros Documentos";
$label_admin_password = "Alterar a Senha de Usuário";
$label_admin_edit_account = "Editar Conta de Usuário";

$label_account_summary = "Resumo da Minha Conta";
$label_confirmed_entries = "Amostras Confirmadas";
$label_unpaid_confirmed_entries = "Amostras Confirmadas e NÃO Pagas";
$label_total_entry_fees = "Total das taxas";
$label_entry_fees_to_pay = "Taxas não pagas";
$label_entry_drop_off = "Entrega presencial";
$label_maintenance = "Manutenção";
$label_judge_info = "Detalhes do Juiz";
$label_cellar = "Meu Porão";
$label_verify = "Verificar";
$label_entry_number = "Número da Amostra";

$header_text_000 = "A Instalação foi bem sucedida.";
$header_text_001 = "Você agora está logado e pronto para customizar o site do Concurso.";
$header_text_002 = "Entretanto, as permissões do config.php não puderam ser alteradas.";
$header_text_003 = "É altamente recomendado que você mude as permissões (chmod) do arquivo config.php para 555. Você terá que acessar o arquivo no seu servidor para fazer isso.";
$header_text_004 = "Adicionalmente, a variável &#36;setup_free_access no config.php está marcada como TRUE. Por razões de segurança, você deve alterá-la para FALSE. Será preciso editar o arquivo config.php.";
$header_text_005 = "Detalhes adicionados com sucesso.";
$header_text_006 = "Detalhes editados com sucesso.";
$header_text_007 = "Ocorreu um erro.";
$header_text_008 = "Tente novamente.";
$header_text_009 = "Você precisa ser um administrador para acessar funções administrativas.";
$header_text_010 = "Alterar";
$header_text_011 = $label_email;
$header_text_012 = $label_password;
$header_text_013 = "O endereço de email já está em uso. Por favor, insira um outro endereço.";
$header_text_014 = "Houve um problema com a última requisição, favor tentar novamente.";
$header_text_015 = "Sua senha atual está incorreta.";
$header_text_016 = "Favor informar um endereço de email.";
$header_text_017 = "Desculpe, houve um problema com a sua última tentativa de login.";
$header_text_018 = "Desculpe, o nome de usuário já está em uso.";
$header_text_019 = "Já verificou se você já criou uma conta?";
$header_text_020 = "Se sim, faça login aqui.";
$header_text_021 = "O nome de usuário não é um email válido.";
$header_text_022 = "Favor inserir um endereço de email válido.";
$header_text_023 = "CAPTCHA malsucedido.";
$header_text_024 = "Os endereços de email inseridos não são iguais.";
$header_text_025 = "The AHA number you entered is already in the system.";
$header_text_026 = "O seu pagamento online foi recebido e a transação foi completada. Aguarde alguns minutos para que o status do pagamento seja atualizado aqui - atualize a página ou acesse a sua lista de amostras. Você receberá um recibo por email do PayPal.";
$header_text_027 = "Não esqueça de imprimir o recibo e anexar a uma das suas amostras como comprovante de pagamento.";
$header_text_028 = "O seu pagamento online foi cancelado.";
$header_text_029 = "O código foi verificado.";
$header_text_030 = "Desculpe, mas o código inserido está incorreto.";
$header_text_031 = "Você deve estar logado e ser admin para acessar as funções administrativas.";
$header_text_032 = "Desculpe, houve um problema com a sua última tentativa de login.";
$header_text_033 = "Certifique-se que o seu email e senha estão corretos.";
$header_text_034 = "Um código para resetar a sua senha foi gerado e enviado ao seu email.";
$header_text_035 = "- agora você pode logar com a sua nova senha.";
$header_text_036 = "Você saiu.";
$header_text_037 = "Logar novamente?";
$header_text_038 = "Suas perguntas de verificação não coincidem com o que está no banco de dados.";
$header_text_039 = "Your ID verification information has been sent to the email address associated with your account.";
$header_text_040 = "Sua mensagem foi enviada para";
$header_text_041 = $header_text_023;
$header_text_042 = "Seu endereço de email foi atualizado.";
$header_text_043 = "Sua senha foi atualizada.";
$header_text_044 = "Detalhes apagados com sucesso.";
$header_text_045 = "Você deve verificar todas as suas amostras importadas usando BeerXML.";
$header_text_046 = "Você se registrou.";
$header_text_047 = "Você alcançou o limite de amostras.";
$header_text_048 = "Sua amostra não foi adicionada.";
$header_text_049 = "Você alcançou o limite de amostras para a subcategoria.";
$header_text_050 = "Set Up: Instalar tabelas e dados na DB";
$header_text_051 = "Set Up: Criar Usuário Admin";
$header_text_052 = "Set Up: Adicionar Detalhes do Admin";
$header_text_053 = "Set Up: Configurar Website";
$header_text_054 = "Set Up: Adicionar Detalhes do Concurso";
$header_text_055 = "Set Up: Adicionar Locais de Julgamento";
$header_text_056 = "Set Up: Adicionar Locais de Recebimento";
$header_text_057 = "Set Up: Designar Estilos Aceitos";
$header_text_058 = "Set Up: Configurar Julgamento";
$header_text_059 = "Importar Amostra Usando BeerXML";
$header_text_060 = "Sua amostra foi gravada.";
$header_text_061 = "Sua amostra foi confirmada.";
$header_text_065 = "Todas as amostras recebidas foram verificadas e aquelas não atribuídas a mesas foram atribuídas.";
$header_text_066 = "Detalhes atualizados com sucesso.";
$header_text_067 = "O sufixo inserido já está em uso, favor escolher um diferente.";
$header_text_068 = "Os dados da concurso especificado foram limpos.";
$header_text_069 = "Arquivos criados com sucesso. ";
$header_text_070 = "Clique no nome do arquivo para vê-lo.";
$header_text_071 = "Lembre-se de atualizar o seu ".$label_admin_comp_info." e o seu ".$label_admin_judging_loc." se você estiver iniciando uma nova competição.";
$header_text_072 = "Arquivo \"".$filter."\" apagado.";
$header_text_073 = "Os registros foram atualizados.";
$header_text_074 = "O nome de usuário inserido já está em uso.";
$header_text_075 = "Adicionar outro local de recebimento?";
$header_text_076 = "Adicionar novo local de julgamento, data ou hora?";
$header_text_077 = "A mesa definida não tem estilos associados.";
$header_text_078 = "Um ou mais campos obrigatórios estão faltando - marcados em vermelho abaixo.";
$header_text_079 = "O endereço de email inserido não é igual.";
$header_text_080 = "The AHA number you entered is already in the system.";
$header_text_081 = "Todas as amostras foram marcadas como pagas.";
$header_text_082 = "Todas as amostras foram marcadas como recebidas.";
$header_text_083 = "Todas as amostras não confirmadas estão agora marcadas como confirmadas.";
$header_text_084 = "Todas as tarefas do participante foram cumpridas.";
$header_text_085 = "O número de julgamento inserido não foi encontrado no banco de dados.";
$header_text_086 = "All entry styles have been converted from BJCP 2008 to BJCP 2015.";
$header_text_087 = "Dados apagados com sucesso.";
$header_text_088 = "O juiz/auxiliar foi adicionado com sucesso. Lembre-se de adicionar o usuário como juiz ou auxiliar antes de atribuí-lo a mesas.";
$header_text_089 = "O arquivo foi enviado com sucesso. Veja a lista para verificar.";
$header_text_090 = "O tipo do arquivo enviado não é aceito pelo sistema.";
$header_text_091 = "Arquivo(s) apagados com sucesso.";
$header_text_092 = "O email teste foi gerado. Verifique também a sua pasta de spam.";
$header_text_093 = "A senha do usuário foi alterada. Certifique-se de informá-lo da nova senha!";
$header_text_094 = "A alteração das permissões da pasta user_images para 755 falhou.";
$header_text_095 = "You will need to change the folder&rsquo;s permission manually. Consult your FTP program or ISP&rsquo;s documentation for chmod (folder permissions).";
$header_text_096 = "Os números de julgamento foram gerados novamente.";
$header_text_097 = "Sua instalação foi concluída com sucesso!";
$header_text_098 = "Por RAZÕES DE SEGURANÇA você deve alterar imediatamente a variável &#36;setup_free_access no config.php para FALSE.";
$header_text_099 = "Caso contrário, sua instalação e o seu servidor estarão vulneráveis brechas de segurança.";
$header_text_100 = "Faça Login agora para acessar o Painel de Administração";
$header_text_101 = "Sua instalação foi atualizada com sucesso!";
$header_text_102 = "Os endereços de email não correspondem.";
$header_text_103 = "Por favor, faça o login para acessar sua conta.";
$header_text_104 = "Você não tem privilégios de acesso suficientes para visualizar esta página.";
$header_text_105 = "Mais informações são necessárias para que sua inscrição seja aceita e confirmada.";
$header_text_106 = "Veja a (s) área (s) destacada (s) em RED abaixo.";
$header_text_107 = "Por favor, escolha um estilo.";
$header_text_108 = "Esta amostra não pode ser aceita ou confirmada até que um estilo tenha sido escolhido. Amostras não confirmadas podem ser deletadas do sistema sem aviso prévio.";
$header_text_109 = "Você se registrou como administrador.";
$header_text_110 = "Todas as amostras foram marcadas como pagas.";
$header_text_111 = "Todas as amostras foram marcadas como recebidas.";

$alert_text_000 = "Conceda acesso de administrador e administrador de nível superior aos usuários com cautela.";
$alert_text_001 = "Limpeza de dados concluída.";
$alert_text_002 = "A variável setup_free_access em config.php está atualmente definida como TRUE.";
$alert_text_003 = "Por razões de segurança, a configuração deve retornar para FALSE. Você precisará editar o arquivo config.php diretamente e recarregar o arquivo para o seu servidor.";
$alert_text_005 = "Nenhum local de entrega foi especificado.";
$alert_text_006 = "Adicionar um local de entrega?";
$alert_text_008 = "Nenhuma data/localização de julgamento foi especificada.";
$alert_text_009 = "Adicionar um local de julgamento?";
$alert_text_011 = "Nenhum contato de competição foi especificado.";
$alert_text_012 = "Adicionar um contato de competição?";
$alert_text_014 = "Seu conjunto de estilos atual é BJCP 2008.";
$alert_text_015 = "Você deseja converter todas as amostras para o BJCP 2015?";
$alert_text_016 = "Tem certeza? Esta ação converterá todas as amostras no banco de dados para estar em conformidade com as diretrizes de estilo do BJCP 2015. As categorias serão 1:1 sempre que possível, no entanto, alguns estilos especiais podem precisar ser atualizados pelo participante.";
$alert_text_017 = "Para manter a funcionalidade, a conversão deve ser executada <em>antes</em> de definir tabelas.";
$alert_text_019 = "Todas as amostras não confirmadas foram apagadas da base de dados.";
$alert_text_020 = "As amostras não confirmadas são destacadas e denotadas com um ícone <span class =\"fa-lg fa-lg-exclamation-triangle text-danger\"></span>abaixo.";
$alert_text_021 = "Os proprietários dessas amostras devem ser contatados. Essas amostras não estão incluídas nos cálculos de taxas.";
$alert_text_023 = "Adicionar um local de entrega?";
$alert_text_024 = $label_yes;
$alert_text_025 = $label_no;
$alert_text_027 = "O registro da inscrição ainda não foi aberto.";
$alert_text_028 = "O registro da inscrição foi encerrado.";
$alert_text_029 = "Adicionar amostras não está disponível.";
$alert_text_030 = "O limite de amostras da competição foi atingido.";
$alert_text_031 = "Seu limite pessoal de amostras foi atingido.";
$alert_text_032 = "Você poderá adicionar amostras a partir de ".$entry_open.".";
$alert_text_033 = "O registro da conta será aberto em ".$reg_open.".";
$alert_text_034 = "Por favor, retorne para registrar sua conta.";
$alert_text_036 = "O registro da inscrição será aberto em ".$entry_open.".";
$alert_text_037 = "Por favor, retorne para adicionar suas amostras ao sistema.";
$alert_text_039 = "O registro de juiz e assistente será aberto em ".$judge_open.".";
$alert_text_040 = "Por favor, retorne para registrar-se como juiz ou mordomo.";
$alert_text_042 = "Inscrições abertas!";
$alert_text_043 = "Um total de ".$total_entries. " amostras foram adicionadas ao sistema a partir de ".$current_time.".";
$alert_text_044 = "O registro será fechado";
$alert_text_046 = "O limite de amostras quase foi alcançado!";
$alert_text_047 = $total_entries." de ".$row_limits ['prefsEntryLimit']." amostras foram adicionadas ao sistema a partir de ".$current_time.".";
$alert_text_049 = "O limite de amostras foi atingido.";
$alert_text_050 = "O limite de ".$row_limits ['prefsEntryLimit']. " amostras foi atingido. Nenhuma outra amostra será aceita.";
$alert_text_052 = "O limite de amostras pagas foi atingido.";
$alert_text_053 = "O limite de ".$row_limits ['prefsEntryLimitPaid']. " amostras <em>pagas</em> foi atingido. Nenhuma outra amostra será aceita.";
$alert_text_055 = "O registro está fechado.";
$alert_text_056 = "Se você já registrou uma conta,";
$alert_text_057 = "faça o login aqui"; // letras minúsculas e falta de pontuação intencional
$alert_text_059 = "O registro da inscrição está fechado.";
$alert_text_060 = "Um total de ". $total_entries. " amostras foram adicionadas ao sistema.";
$alert_text_062 = "A entrega presencial de amostra está encerrada.";
$alert_text_063 = "Garrafas de amostra não são mais aceitas nos locais de entrega.";
$alert_text_065 = "O envio de amostras por correios está encerrado.";
$alert_text_066 = "Garrafas de amostra não são mais aceitas no local de entrega.";
$alert_text_068 = $j_s_text. "registro aberto.";
$alert_text_069 = "Registre-se aqui"; // falta de pontuação intencional
$alert_text_070 = $j_s_text. "o registro será fechado". $judge_closed. ".";
$alert_text_072 = "O limite de juízes registrados foi atingido.";
$alert_text_073 = "O registro de juízes está encerrado.";
$alert_text_074 = "O registro como comissário ainda está disponível.";
$alert_text_076 = "O limite de administradores registrados foi atingido.";
$alert_text_077 = "O registro de assistentes está encerrado.";
$alert_text_078 = "O registro como juiz ainda está disponível.";
$alert_text_080 = "Senha incorreta.";
$alert_text_081 = "Senha aceita.";

$alert_email_valid = "O formato de email é válido!";
$alert_email_not_valid = "O formato de email não é válido!";
$alert_email_in_use = "O endereço de email que você digitou já está em uso. Por favor, escolha outro.";
$alert_email_not_in_use = "Parabéns! O endereço de e-mail que você digitou não está em uso.";

$comps_text_000 = "Escolha a competição que você deseja acessar na lista abaixo.";
$comps_text_001 = "Competição atual:";
$comps_text_002 = "Não há competições com janelas de amostras abertas agora.";
$comps_text_003 = "Não há competições com janelas de amostras fechando nos próximos 7 dias.";

$beerxml_text_000 = "Importar amostras não está disponível.";
$beerxml_text_001 = "foi enviado e o brew foi adicionado à sua lista de amostras.";
$beerxml_text_002 = "Desculpe, este tipo de arquivo não pode ser enviado. Somente extensões de arquivo .xml são permitidas.";
$beerxml_text_003 = "O tamanho do arquivo é superior a 2MB. Ajuste o tamanho e tente novamente.";
$beerxml_text_004 = "Arquivo inválido especificado.";
$beerxml_text_005 = "No entanto, ele não foi confirmado. Para confirmar sua amostra, acesse sua lista de amostras para mais instruções. Ou, você pode adicionar outra amostra com o BeerXML abaixo.";
$beerxml_text_006 = "A versão do PHP do seu servidor não suporta o recurso de importação BeerXML.";
$beerxml_text_007 = "É necessária a versão 5.x ou superior do PHP & mdash; este servidor está executando a versão do PHP". $php_version. ".";
$beerxml_text_008 = "Procure seu arquivo compatível com BeerXML no seu disco rígido e clique em <em> Upload </em>.";
$beerxml_text_009 = "Escolha o arquivo BeerXML";
$beerxml_text_010 = "Nenhum arquivo escolhido ...";
$beerxml_text_011 = "amostras adicionadas"; // letras minúsculas e falta de pontuação intencional
$beerxml_text_012 = "amostra adicionada"; // letras minúsculas e falta de pontuação intencional

$brew_text_000 = "Clique para detalhes sobre o estilo"; // falta de pontuação intencional
$brew_text_001 = "Os juízes não saberão o nome da sua amostra.";
$brew_text_002 = "[desativado - limite de amostra do estilo atingido]"; // falta de pontuação intencional
$brew_text_003 = "[desativado - limite de amostra do estilo alcançado para o usuário]"; // falta de pontuação intencional
$brew_text_004 = "Tipo específico, ingredientes especiais, estilo clássico, força (para estilos de cerveja) e / ou cor são necessários.";
$brew_text_005 = "Força necessária"; // falta de pontuação intencional
$brew_text_006 = "Nível de carbonatação necessário"; // falta de pontuação intencional
$brew_text_007 = "Nível de doçura necessário"; // falta de pontuação intencional
$brew_text_008 = "Este estilo requer que você forneça informações específicas para a amostra.";
$brew_text_009 = "Requisitos para"; // falta de pontuação intencional
$brew_text_010 = "Este estilo requer mais informações. Por favor, entre na área fornecida.";
$brew_text_011 = "O nome da amostra é obrigatório.";
$brew_text_012 = "*** NÃO REQUERIDO *** Forneça APENAS se você deseja que os juízes considerem completamente o que você escreve aqui ao avaliar e pontuar sua inscrição. Use para registrar detalhes que você gostaria que os juízes considerassem ao avaliar sua inscrição que você NÃO ESPECIFICAR em outros campos (por exemplo, técnica de mostura, variedade de lúpulo, variedade de mel, variedade de uva, variedade de pêra, etc.). ";
$brew_text_013 = "NÃO use este campo para especificar ingredientes especiais, estilo clássico, força (para amostras de cerveja) ou cor.";
$brew_text_014 = "Forneça apenas se desejar que os juízes considerem totalmente o que você especificar ao avaliar e pontuar sua inscrição.";
$brew_text_015 = "Tipo de extrato (por exemplo, claro, escuro) ou marca.";
$brew_text_016 = "Tipo de grão (por exemplo, pilsner, pale ale, etc.)";
$brew_text_017 = "Tipo de ingrediente ou nome.";
$brew_text_018 = "Nome do lúpulo";
$brew_text_019 = "Somente números.";
$brew_text_020 = "Nome da linhagem (por exemplo, 1056 American Ale).";
$brew_text_021 = "Wyeast, White Labs, etc.";
$brew_text_022 = "1 smackpack, 2 frascos, 2000 ml, etc.";
$brew_text_023 = "Fermentação primária em dias.";
$brew_text_024 = "Descanso de sacarificação, etc.";
$brew_text_025 = "Fermentação secundária em dias.";
$brew_text_026 = "Outra fermentação em dias.";

$brewer_text_000 = "Por favor digite apenas <em> um </em> nome da pessoa.";
$brewer_text_001 = "Escolha uma. Esta questão será usada para verificar sua identidade caso você esqueça sua senha.";
$brewer_text_003 = "Para ser considerado para uma oportunidade GABF Pro-Am você deve ser um membro da AHA.";
$brewer_text_004 = "Forneça qualquer informação que você acredite que o organizador da competição deva conhecer (por exemplo, alergias, restrições alimentares especiais, tamanho da camisa, etc.)";
$brewer_text_005 = "Não Aplicável";
$brewer_text_006 = "Você está disposto e qualificado para servir como juiz nesta competição?";
$brewer_text_007 = "Você passou no exame BJCP Mead Judge?";
$brewer_text_008 = "* A classificação <em> Não-BJCP </em> é para aqueles que não fizeram o Exame de Admissão ao Juiz de Cerveja BJCP, e não são <em> uma cervejaria profissional.";
$brewer_text_009 = "** A classificação <em> Provisória </em> é para aqueles que passaram e passaram no Exame BJCP Beer Judge, mas ainda não fizeram o Exame de Julgamento de Cerveja BJCP.";
$brewer_text_010 = "Apenas as duas primeiras designações aparecerão nos seus rótulos impressos em planilhas.";
$brewer_text_011 = "Quantas competições você já serviu como um <strong>".strtolower ($label_judge)."</ strong>?";
$brewer_text_012 = "Somente para preferências. Deixar um estilo desmarcado indica que você está disponível para julgá-lo - não há necessidade de verificar todos os estilos que você está disponível para julgar.";
$brewer_text_013 = "Clique ou toque no botão acima para expandir os estilos não preferidos para a lista de julgamentos.";
$brewer_text_014 = "Não há necessidade de marcar os estilos para os quais você tem amostras; o sistema não permitirá que você seja atribuído a nenhuma tabela onde você tenha amostras.";
$brewer_text_015 = "Você está disposto a servir como mordomo nesta competição?";
$brewer_text_016 = "Minha participação neste julgamento é totalmente voluntária. Eu sei que a participação neste julgamento envolve o consumo de bebidas alcoólicas e que este consumo pode afetar minhas percepções e reações.";
$brewer_text_017 = "Clique ou toque no botão acima para expandir os estilos preferidos para a lista de jurados.";
$brewer_text_018 = "Ao marcar esta caixa, eu estou efetivamente assinando um documento legal no qual eu aceito a responsabilidade por minha conduta, comportamento e ações e absolvo completamente a competição e seus organizadores, individual ou coletivamente, da responsabilidade por minha conduta, comportamento e ações. ";
$brewer_text_019 = "Se você planeja servir como juiz em qualquer competição, clique ou toque no botão acima para inserir suas informações relacionadas ao juiz.";
$brewer_text_020 = "Você está disposto a servir como membro da equipe nesta competição?";
$brewer_text_021 = "Equipe da competição são pessoas que atuam em várias funções para ajudar na organização e execução da competição antes, durante e após o julgamento. Juízes e mordomos também podem servir como membros da equipe. Os membros da equipe podem ganhar pontos BJCP se a competição é sancionado. ";

$contact_text_000 = "Use os links abaixo para contatar as pessoas envolvidas na coordenação desta competição:";
$contact_text_001 = "Use o formulário abaixo para entrar em contato com um oficial da competição. Todos os campos com uma estrela são obrigatórios.";
$contact_text_002 = "Além disso, uma cópia foi enviada para o endereço de e-mail que você forneceu.";
$contact_text_003 = "Deseja enviar outra mensagem?";

$default_page_text_000 = "Nenhum local de entrega foi especificado.";
$default_page_text_001 = "Adicionar um local de entrega?";
$default_page_text_002 = "Nenhuma data / local de julgamento foi especificado.";
$default_page_text_003 = "Adicionar um local de julgamento?";
$default_page_text_004 = "Amostras vencedoras";
$default_page_text_005 = "Os vencedores serão publicados em ou depois de";
$default_page_text_006 = "Bem-vindo";
$default_page_text_007 = "Veja os detalhes da sua conta e lista de amostras.";
$default_page_text_008 = "Veja os detalhes da sua conta aqui.";
$default_page_text_009 = "Melhor dos Vencedores do Show";
$default_page_text_010 = "Amostras vencedoras";
$default_page_text_011 = "Você só precisa registrar suas informações uma vez e pode retornar a este site para inserir mais cervejas ou editar as cervejas que você inseriu.";
$default_page_text_012 = "Você pode até pagar suas taxas de inscrição online, se desejar.";
$default_page_text_013 = "Oficial da Competição";
$default_page_text_014 = "Funcionários da competição";
$default_page_text_015 = "Você pode enviar um email para qualquer um dos seguintes indivíduos via";
$default_page_text_016 = "tem orgulho de ter o seguinte";
$default_page_text_017 = "para o";
$default_page_text_018 = "Faça o download dos vencedores do Best of Show no formato PDF.";
$default_page_text_019 = "Faça o download dos vencedores do Best of Show no formato HTML.";
$default_page_text_020 = "Faça o download das amostras vencedoras no formato PDF.";
$default_page_text_021 = "Faça o download das amostras vencedoras no formato HTML.";
$default_page_text_022 = "Obrigado pelo seu interesse no";
$default_page_text_023 = "organizado por";

$reg_open_text_000 = "O registro de juiz e assistente está";
$reg_open_text_001 = "Aberto";
$reg_open_text_002 = "Se você <em>não tiver</em> registrado e estiver disposto a ser um voluntário,";
$reg_open_text_003 = "registre-se por favor";
$reg_open_text_004 = "Se você <em>tiver</em> registrado, faça o login e escolha <em>Editar conta</​​em> no menu Minha conta indicado pelo";
$reg_open_text_005 = "ícone no menu superior.";
$reg_open_text_006 = "Já que você já se registrou, você pode";
$reg_open_text_007 = "verificar as informações da sua conta";
$reg_open_text_008 = "para ver se você indicou que você está disposto a julgar e/ou administrar.";
$reg_open_text_009 = "Se você está disposto a julgar ou administrar, por favor retorne para registrar em ou depois de";
$reg_open_text_010 = "As inscrições de amostras estão";
$reg_open_text_011 = "Para adicionar suas amostras no sistema";
$reg_open_text_012 = "prossiga pelo processo de registro";
$reg_open_text_013 = "se você já possui uma conta.";
$reg_open_text_014 = "use o formulário de adicionar uma amostra";

$reg_open_text_015 = "O registro do juiz está";
$reg_open_text_016 = "O registro de assistente está";
$reg_closed_text_000 = "Obrigado e boa sorte a todos que entraram no";
$reg_closed_text_001 = "Existem";
$reg_closed_text_002 = "participantes registrados, juízes e administradores.";
$reg_closed_text_003 = "amostras registradas e";
$reg_closed_text_004 = "participantes registrados, juízes e administradores.";
$reg_closed_text_005 = "A partir de";
$reg_closed_text_006 = "amostras recebidas e processadas (esse número será atualizado conforme as amostras forem retiradas dos locais de entrega e organizadas para julgamento).";
$reg_closed_text_007 = "As datas de julgamento da competição ainda serão determinadas. Por favor, volte mais tarde.";
$reg_closed_text_008 = "Mapear para";
$judge_closed_000 = "Obrigado a todos que participaram do";
$judge_closed_001 = "Houve";
$judge_closed_002 = "amostras julgadas e";
$judge_closed_003 = "participantes registrados, juízes e administradores.";

$entry_info_text_000 = "Você poderá criar sua conta começando";
$entry_info_text_001 = "até";
$entry_info_text_002 = "Juízes e assistentes podem se registrar no começo";
$entry_info_text_003 = "por amostra";
$entry_info_text_004 = "Você pode criar sua conta de usuário até";
$entry_info_text_005 = "Juízes e administradores podem se inscrever até";
$entry_info_text_006 = "Inscrições para";
$entry_info_text_007 = "juízes e administradores apenas";
$entry_info_text_008 = "aceite através de";
$entry_info_text_009 = "O registro é <strong class=\"text-danger\">fechado</strong>.";
$entry_info_text_010 = "Bem-vindo";
$entry_info_text_011 = "Veja os detalhes da sua conta e lista de amostras.";
$entry_info_text_012 = "Veja as informações da sua conta aqui.";
$entry_info_text_013 = "por amostra após o";
$entry_info_text_014 = "Você poderá adicionar suas amostras ao sistema começando";
$entry_info_text_015 = "Você pode adicionar suas amostras ao sistema hoje por meio de";
$entry_info_text_016 = "O registro de amostras está <strong class=\"text-danger\">fechado</ strong>.";
$entry_info_text_017 = "para amostras ilimitadas.";
$entry_info_text_018 = "para membros da AHA.";
$entry_info_text_019 = "Existe um limite de";
$entry_info_text_020 = "amostras para esta competição.";
$entry_info_text_021 = "Cada participante está limitado a";
$entry_info_text_022 = strtolower($label_entry);
$entry_info_text_023 = strtolower($label_entries);
$entry_info_text_024 = "amostra por subcategoria";
$entry_info_text_025 = "amostras por subcategoria";
$entry_info_text_026 = "exceções são detalhadas abaixo";
$entry_info_text_027 = strtolower ($label_subcategory);
$entry_info_text_028 = "subcategorias";
$entry_info_text_029 = "amostra para o seguinte";
$entry_info_text_030 = "amostras para o seguinte";
$entry_info_text_031 = "Depois de criar sua conta e adicionar suas amostras no sistema, você deve pagar sua(s) taxa(s) de amostra. As formas de pagamento aceitas são:";
$entry_info_text_032 = $label_cash;
$entry_info_text_033 = $label_check.", feito para";
$entry_info_text_034 = "Cartão de crédito / débito e cheque eletrônico, via PayPal";
$entry_info_text_035 = "As datas de julgamento da competição ainda serão determinadas. Por favor, volte mais tarde.";
$entry_info_text_036 = "Amostras aceitas no nosso local de recebimento de";
$entry_info_text_037 = "Enviar amostras para:";
$entry_info_text_038 = "Embale cuidadosamente as suas amostras em uma caixa resistente. Forre o interior da sua caixa com um saco de lixo. Embale cada garrafa separadamente com material de embalagem adequado. Por favor, não amontoe!";
$entry_info_text_039 = "Escreva claramente: <em>Frágil. Este lado para cima.</em> na embalagem. Por favor, use apenas plástico-bolha como material de embalagem.";
$entry_info_text_040 = "Coloque <em>cada</em> dos rótulos das garrafas em uma pequena sacola plástica antes de prendê-los às respectivas garrafas. Dessa forma, o organizador pode identificar especificamente qual amostra quebrou se houver danos durante o transporte. ";
$entry_info_text_041 = "Todo esforço razoável será feito para contatar os participantes cujas garrafas quebraram para providenciar o envio de garrafas de reposição.";
$entry_info_text_042 = "Se você mora nos Estados Unidos, por favor, note que é <strong>ilegal</strong> enviar suas inscrições através do Serviço Postal dos Estados Unidos (USPS). Empresas de transporte privadas têm o direito de recusar sua remessa se eles são informados de que o pacote contém bebidas de vidro e/ou bebidas alcoólicas. Esteja ciente de que as encomendas enviadas internacionalmente são frequentemente solicitadas pela alfândega para ter a devida documentação. Essas amostras podem ser abertas e/ou devolvidas ao remetente por funcionários alfandegários a seu critério. É de sua inteira responsabilidade seguir todas as leis e regulamentos aplicáveis.";
$entry_info_text_043 = "Amostras aceitas em nossos locais de entrega presencial de";
$entry_info_text_044 = "Abrir mapa";
$entry_info_text_045 = "Clique para saber informações obrigatórias das amostras";
$entry_info_text_046 = "Se o nome de um estilo é um link, a amostra possui requisitos específicos. Clique no nome para ver os requisitos da subcategoria.";

$brewer_entries_text_000 = "Há um problema conhecido com a impressão do navegador Firefox.";
$brewer_entries_text_001 = "Você tem amostras não confirmadas.";
$brewer_entries_text_002 = "Para cada amostra abaixo com o ícone <span class=\"fa-lg fa-exclamation-circle text-danger\"></span>, clique no ícone <span class=\"fa fa-lg fa-pencil text-primary\"></span> para revisar e confirmar todos os dados das amostras. As amostras não confirmadas podem ser excluídas do sistema sem aviso prévio.";
$brewer_entries_text_003 = "Você NÃO PODE pagar pelas suas inscrições até que todas as inscrições sejam confirmadas.";
$brewer_entries_text_004 = "Você tem amostras que exigem que você defina um tipo específico, ingredientes especiais, estilo clássico, força e/ou cor.";
$brewer_entries_text_005 = "Para cada amostra abaixo com um ícone <span class =\"fa-lg fa-exclamation-circle text-danger\"></ span>, clique no ícone <span class=\"fa fa-lg fa ícone de texto-primário com lápis\"></span> para inserir as informações necessárias. Amostras sem um tipo específico, ingredientes especiais, estilo clássico, força e/ou cor nas categorias que os exigem podem ser excluídas pelo sistema sem aviso.";
$brewer_entries_text_006 = "Faça o download de planilhas de juízes&rsquo; para";
$brewer_entries_text_007 = "Estilo não inserido";
$brewer_entries_text_008 = "Formulário de Inscrição e";
$brewer_entries_text_009 = "Rótulos de Garrafas";
$brewer_entries_text_010 = "Imprimir formulário de receita para";
$brewer_entries_text_011 = "Além disso, você não poderá adicionar outra amostra caso o limite de amostras para a competição tenha sido alcançado. Clique em Cancelar nesta caixa e depois edite a amostra se você quiser mantê-la.";
$brewer_entries_text_012 = "Tem certeza que deseja apagar a amostra chamada";
$brewer_entries_text_013 = "Você poderá adicionar amostras a partir de";
$brewer_entries_text_014 = "Você não adicionou nenhuma amostra ao sistema.";
$brewer_entries_text_015 = "Você não pode deletar sua amostra neste momento.";

$past_winners_text_000 = "Ver vencedores anteriores:";

$pay_text_000 = "a janela de pagamento passou.";
$pay_text_001 = "Entre em contato com um oficial da competição se tiver alguma dúvida.";
$pay_text_002 = "as seguintes são as opções para pagar as taxas de inscrição.";
$pay_text_003 = "Taxas são";
$pay_text_004 = "por amostra";
$pay_text_005 = "por amostra após o";
$pay_text_006 = "para amostras ilimitadas";
$pay_text_007 = "Suas taxas foram descontadas para";
$pay_text_008 = "Suas taxas totais de inscrição são";
$pay_text_009 = "Você precisa pagar";
$pay_text_010 = "Suas taxas foram marcadas como pagas. Obrigado!";
$pay_text_011 = "Você ainda não pagou";
$pay_text_012 = "das sua(s)";
$pay_text_013 = "Anexe um cheque com o valor global em uma de suas garrafas. Os cheques deverão estar cruzados e nominais para";
$pay_text_014 = "Guarde uma cópia do cheque. Não haverá recibo.";
$pay_text_015 = "Anexar pagamento em dinheiro para o valor global das taxas em um <em>envelope lacrado</em> em uma de suas garrafas.";
$pay_text_016 = "As folhas de resultados retornados servirão como recibo da amostra.";
$pay_text_017 = "O seu email de confirmação de pagamento é o seu recibo da amostra. Inclua uma cópia com as suas amostras como prova de pagamento.";
$pay_text_018 = "Clique no botão <em>Pagar com PayPal</em> abaixo para pagar on-line.";
$pay_text_019 = "Por favor, note que uma taxa de transação de";
$pay_text_020 = "será adicionado ao seu total.";
$pay_text_021 = "Para garantir que seu pagamento pelo PayPal esteja marcado como <strong>pago</strong> no <strong>site</strong>, clique no link <em> Retornar para ... </em> Tela de confirmação do PayPal <strong>após</strong> você enviou o seu pagamento. Além disso, certifique-se de imprimir o comprovante de pagamento e anexá-lo a uma de suas amostras. ";
$pay_text_022 = "Certifique-se de clicar em <em>Retornar para ...</em> depois de pagar suas taxas";
$pay_text_023 = "Digite o código fornecido pelos organizadores da competição para uma taxa de amostra com desconto.";
$pay_text_024 = $pay_text_010;
$pay_text_025 = "Você ainda não inscreveu nenhuma amostra.";
$pay_text_026 = "Você não pode pagar por suas amostras porque uma ou mais de suas amostras não estão confirmadas.";
$pay_text_027 = "Clique em <em>Minha conta</​​em> acima para revisar suas amostras não confirmadas.";
$pay_text_028 = "Você tem amostras não confirmadas que <em>não</em> estão refletidas em seus totais de taxas abaixo.";
$pay_text_029 = "Por favor, vá para a sua lista de amostras para confirmar todos os dados da sua amostra. As amostras não confirmadas podem ser excluídas do sistema sem aviso.";

if (strpos ($view, "^")!== FALSE) {
    $qr_text_019 =sprintf ("%06d", $checked_in_numbers [0]);
    if (is_numeric ($checked_in_numbers [1])) $qr_text_020 = sprintf ("%06d", $checked_in_numbers [1]);
    else $qr_text_020 = $checked_in_numbers [1];
}

$qr_text_000 = $alert_text_080;
$qr_text_001 = $alert_text_081;

// Comece traduções aqui
if (strpos($view, "^") !== FALSE) $qr_text_002 = sprintf("Número de amostra <span class=\"text-danger\">%s</span> é verificado com <span class=\"text-danger\">%s</ span> como seu número de julgamento.",$qr_text_019,$qr_text_020); else $qr_text_002 = "";
$qr_text_003 = "Se este número de avaliação não for <em> </em> correto, <strong> verifique novamente o código e digite novamente o número de avaliação correto.";
if (strpos ($view, "^")!== FALSE) $qr_text_004 = sprintf ("Número da amostra %s está marcado", $qr_text_019); else $qr_text_004 = "";
if (strpos ($view, "^")!== FALSE) $qr_text_005 = sprintf ("Número da amostra %s não foi encontrado no banco de dados. Deixe a(s) garrafa(s) de lado e alerte o organizador da competição.", $qr_text_019 ); else $qr_text_005 = "";
if (strpos ($view, "^")!== FALSE) $qr_text_006 = sprintf ("O número do julgamento que você digitou - %s - já está atribuído ao número de amostra %s.", $qr_text_020, $qr_text_019); else $qr_text_006 = "";
$qr_text_007 = "Check-in de amostra por código QR";
$qr_text_008 = "Para fazer o check-in das amostras por meio do código QR, forneça a senha correta. Você só precisará fornecer a senha uma vez por sessão - certifique-se de manter o aplicativo de verificação de código QR aberto.";
$qr_text_009 = "Atribuir um número de julgamento e/ou número de caixa à amostra";
$qr_text_010 = "SOMENTE insira um número de julgamento se o seu concorrente estiver usando o número de marcadores na ordenação.";
$qr_text_011 = "Seis números com zeros à esquerda - por exemplo, 000021.";
$qr_text_012 = "Certifique-se de verificar novamente sua amostra e afixar os rótulos dos números de julgamento apropriados em cada rótulo de garrafa e frasco (se aplicável).";
$qr_text_013 = "Os números de julgamento devem ter seis caracteres e não podem incluir o caractere ^.";
$qr_text_014 = "Esperando pela entrada do código QR digitalizado.";
$qr_text_015 = "Inicie ou volte ao aplicativo de escaneamento do seu dispositivo móvel para escanear um código QR.";
$qr_text_016 = "Precisa de um aplicativo de verificação de código QR? <a href=\"https://play.google.com/store/search?q=qr%20code%20scanner&c=apps&hl=en\" target=\"_blank\">Google Play</a> (Android) ou <a href=\"https://itunes.apple.com/store/\" target=\"_blank\">iTunes</a> (iOS).";
$qr_text_017 = "É necessário um aplicativo de escaneamento de QR Code para utilizar este recurso.";
$qr_text_018 = "Inicie o aplicativo em seu dispositivo móvel, digitalize um Código QR localizado em um rótulo de garrafa, insira a senha solicitada e faça o check-in da amostra.";

$register_text_000 = "É o voluntário";
$register_text_001 = "Você está";
$register_text_002 = "O registro foi encerrado.";
$register_text_003 = "Obrigado pelo seu interesse.";
$register_text_004 = "As informações que você fornecer além do seu primeiro nome, sobrenome e clube são estritamente para fins de registro e manutenção.";
$register_text_005 = "Uma condição para entrar na competição é fornecer esta informação. Seu nome e clube podem ser exibidos no caso de uma das suas inscrições, mas nenhuma outra informação será tornada pública.";
$register_text_006 = "Lembrete: Você só tem permissão para entrar em uma região e depois de se registrar em um local, você NÃO poderá alterá-la.";
$register_text_007 = "Rápido";
$register_text_008 = "Registrar";
$register_text_009 = "um juiz/administrador";
$register_text_010 = "um participante";
$register_text_011 = "Para se registrar, crie sua conta online preenchendo os campos abaixo.";
$register_text_012 = "Adicione rapidamente um participante juiz ou assistente. Um endereço fictício e número de telefone serão usados ​​e uma senha padrão de <em>bcoem</em> será dado a cada participante adicionado através desta tela.";
$register_text_013 = "A inscrição nesta competição é realizada completamente online.";
$register_text_014 = "Para adicionar suas amostras e/ou indicar que você está disposto a julgar ou administrar (juízes e assitentes também podem adicionar amostras), você precisará criar uma conta em nosso sistema.";
$register_text_015 = "Seu endereço de e-mail será seu nome de usuário e será usado como meio de divulgação de informações pela equipe da competição. Verifique se está correto.";
$register_text_016 = "Depois de se registrar, você pode continuar o processo de inscrição.";
$register_text_017 = "Cada amostra que você adicionar será automaticamente atribuída a um número pelo sistema.";
$register_text_018 = "Escolha uma. Esta questão será usada para verificar sua identidade caso você esqueça sua senha.";
$register_text_019 = "Por favor, forneça um endereço de e-mail.";
$register_text_020 = "Os endereços de e-mail que você digitou não são iguais.";
$register_text_021 = "Endereços de e-mail servem como nomes de usuários.";
$register_text_022 = "Por favor, forneça uma senha.";
$register_text_023 = "Por favor, forneça uma resposta à sua pergunta de segurança.";
$register_text_024 = "Faça a sua segurança responder a algo que só você se lembrará facilmente!";
$register_text_025 = "Por favor, forneça um primeiro nome.";
$register_text_026 = "Por favor, forneça um sobrenome.";
$register_text_027 = "";
$register_text_028 = "Por favor, forneça um endereço.";
$register_text_029 = "Por favor, forneça uma cidade.";
$register_text_030 = "Por favor, forneça um estado.";
$register_text_031 = "Por favor, forneça um CEP ou código postal.";
$register_text_032 = "Por favor, forneça um número de telefone principal.";
$register_text_033 = "Somente os membros da American Homebrewers Association são elegíveis para uma oportunidade do Great American Beer Festival Pro-Am.";
$register_text_034 = "Para se registrar, você deve marcar a caixa, indicando que concorda com a declaração de renúncia.";
$register_text_035 = "Favor preencher um CPF válido.";

$sidebar_text_000 = "Inscrições para juízes ou assitentes";
$sidebar_text_001 = "Inscrições para assitentes";
$sidebar_text_002 = "Inscrições para juízes";
$sidebar_text_003 = "Inscrições encerradas. O limite de juízes e assistentes foi atingido.";
$sidebar_text_004 = "até";
$sidebar_text_005 = "O registro de usuários estará aberto de";
$sidebar_text_006 = "está aberto apenas para juízes ou assistentes";
$sidebar_text_007 = "está aberto apenas para assistentes";
$sidebar_text_008 = "está aberto apenas para juízes";
$sidebar_text_009 = "A inscrição de amostras estará aberta de ";
$sidebar_text_010 = "O limite de amostras pagas da competição foi atingido.";
$sidebar_text_011 = "O limite de amostras da competição foi atingido.";
$sidebar_text_012 = "Veja sua lista de amostras.";
$sidebar_text_013 = "Clique aqui para pagar suas taxas das amostras.";
$sidebar_text_014 = "As taxas de inscrição não incluem amostras não confirmadas.";
$sidebar_text_015 = "Você tem amostras não confirmadas - a ação é necessária para confirmar.";
$sidebar_text_016 = "Veja sua lista de amostras.";
$sidebar_text_017 = "Você tem";
$sidebar_text_018 = "restantes antes de atingir o limite de";
$sidebar_text_019 = "por participante";
$sidebar_text_020 = "Você atingiu o limite de";
$sidebar_text_021 = "nesta competição";
$sidebar_text_022 = "Os amostras poderão ser entregues no";
$sidebar_text_023 = "endereço para recebimento por correios de";
$sidebar_text_024 = "As datas de julgamento da competição ainda serão determinadas. Por favor, volte mais tarde.";
$sidebar_text_025 = "foram adicionadas ao sistema a partir de";

$styles_entry_text_07C = "O participante deve especificar se a amostra é um Munich Kellerbier (pálido, baseado em Helles) ou um Franconian Kellerbier (âmbar, baseado em Marzen). O participante pode especificar outro tipo de Kellerbier baseado em outros estilos base, como Pils. , Bock, Schwarzbier, mas deve fornecer uma descrição de estilo para os juízes. ";
$styles_entry_text_09A = "O participante deve especificar se a amostra é uma variante pálida ou escura.";
$styles_entry_text_10C = "O participante deve especificar se a amostra é uma variante pálida ou escura.";
$styles_entry_text_21B = "O participante deve especificar uma força (sessão: 3.0-5.0%, padrão: 5.0-7.5%, duplo: 7.5-9.5%); se nenhuma força for especificada, o padrão será assumido. O participante deve especificar um tipo específico de especialidade IPA da biblioteca de tipos conhecidos listados nas Diretrizes de Estilo, ou emendadas pelo site do BJCP, ou o participante deve descrever o tipo de Especialidade IPA e suas principais características no formulário de comentário para que os jurados saibam o que esperar. Variedades de lúpulo específicas usadas, se os participantes sentirem que os juízes podem não reconhecer as características varietais do lúpulo mais novo Os participantes podem especificar uma combinação de tipos definidos de IPA (por exemplo, Black Rye IPA) sem fornecer descrições adicionais. versão de um IPA definido por sua própria subcategoria BJCP (por exemplo, IPA americano ou inglês de força de sessão) - exceto onde já existe uma subcategoria BJCP para esse estilo (por exemplo, duplo IPA [americano]). Tipos: IPA preto, IPA castanho, IPA branco, IPA de centeio, IPA belga, IPA vermelho. ";
$styles_entry_text_23F = "O tipo de fruta usado deve ser especificado. O cervejeiro deve declarar um nível de carbonatação (baixo, médio, alto) e um nível de doçura (baixo / nenhum, médio, alto).";
$styles_entry_text_24C = "O participante deve especificar bière de louro, âmbar ou marrom. Se nenhuma cor for especificada, o juiz deve tentar julgar com base na observação inicial, esperando um sabor e um equilíbrio de malte que correspondam à cor.";
$styles_entry_text_25B = "O participante deve especificar a força (tabela, padrão, super) e a cor (claro, escuro).";
$styles_entry_text_27A = "O participante deve especificar um estilo com uma descrição fornecida pelo BJCP, ou fornecer uma descrição semelhante para os juízes de um estilo diferente. Se uma cerveja é inserida apenas com um nome de estilo e sem descrição, é muito improvável que os juízes entenderão como julgá-lo: Exemplos atualmente definidos: Gose, Piwo Grodziskie, Lichtenhainer, Roggenbier, Sahti, Kentucky Common, Pre-Prohibition Lager, Pre-Prohibition Porter, London Brown Ale. ";
$styles_entry_text_28A = "O participante deve especificar um estilo de cerveja base (estilo BJCP clássico ou uma família de estilo genérico) ou fornecer uma descrição dos ingredientes / especificações / caractere desejado. O participante deve especificar se uma fermentação de Brett 100% foi conduzida. O participante pode especificar a (s) estirpe (s) de Brettanomyces utilizadas, juntamente com uma breve descrição do seu caráter. ";
$styles_entry_text_28B = "O participante deve especificar uma descrição da cerveja, identificando as leveduras / bactérias usadas e um estilo base ou os ingredientes / especificações / caráter alvo da cerveja.";
$styles_entry_text_28C = "O participante deve especificar o tipo de fruta, especiaria, erva ou madeira usada. O participante deve especificar uma descrição da cerveja, identificando as leveduras / bactérias usadas e um estilo de base ou os ingredientes / especificações / caráter alvo da cerveja. Uma descrição geral da natureza especial da cerveja pode abranger todos os itens necessários. ";
$styles_entry_text_29A = "O participante deve especificar um estilo base; o estilo declarado não precisa ser um estilo Clássico. O participante deve especificar o tipo de fruta usada. Cervejas de frutas com sabor que não são lambics devem ser inseridas no American Wild Ale categoria.";
$styles_entry_text_29B = "O participante deve especificar um estilo base; o estilo declarado não precisa ser um Estilo Clássico. O participante deve especificar o tipo de fruta e especiarias, ervas ou vegetais (SHV) utilizados; os ingredientes SHV individuais não precisam a especificar se for usada uma mistura bem conhecida de especiarias (por exemplo, torta de maçã). ";
$styles_entry_text_29C = "O participante deve especificar um estilo de base; o estilo declarado não precisa ser um Estilo Clássico. O participante deve especificar o tipo de fruto usado. O participante deve especificar o tipo de açúcar fermentável adicional ou processo especial empregado." ;
$styles_entry_text_30A = "O participante deve especificar um estilo base; o estilo declarado não precisa ser um estilo Clássico. O participante deve especificar o tipo de especiarias, ervas ou vegetais utilizados; ingredientes individuais não precisam ser especificados se um poço conhecida mistura de especiarias é usada (por exemplo, torta de maçã). ";
$styles_entry_text_30B = "O participante deve especificar um estilo base; o estilo declarado não precisa ser um estilo Clássico. O participante deve especificar o tipo de especiarias, ervas ou vegetais utilizados; ingredientes individuais não precisam ser especificados se um poço conhecida mistura de especiarias (por exemplo, torta de abóbora). A cerveja deve conter especiarias e pode conter vegetais e / ou açúcares. ";
$styles_entry_text_30C = "O participante deve especificar um estilo base; o estilo declarado não precisa ser um Estilo Clássico. O participante deve especificar o tipo de especiarias, açúcares, frutas ou fermentescos adicionais usados; ingredientes individuais não precisam ser especificados se for usada uma mistura bem conhecida de especiarias (por exemplo, tempero quente). ";
$styles_entry_text_31A = "O participante deve especificar um estilo base; o estilo declarado não precisa ser um estilo Clássico. O participante deve especificar o tipo de grão alternativo usado.";
$styles_entry_text_31B = "O participante deve especificar um estilo base; o estilo declarado não precisa ser um estilo Clássico. O participante deve especificar o tipo de açúcar usado.";
$styles_entry_text_32A = "O participante deve especificar uma cerveja base de Estilo Clássico. O participante deve especificar o tipo de madeira ou fumaça se um caráter de fumaça varietal for perceptível.";
$styles_entry_text_32B = "O participante deve especificar um estilo de cerveja base; a cerveja base não precisa ser um Estilo Clássico. O participante deve especificar o tipo de madeira ou fumaça se um caráter de fumaça varietal for perceptível. O participante deve especificar os ingredientes adicionais ou processos que fazem desta uma cerveja fumada especializada. ";
$styles_entry_text_33A = "O participante deve especificar o tipo de madeira usado e o nível do char (se carbonizado). O participante deve especificar o estilo base; o estilo base pode ser um estilo BJCP clássico (ou seja, uma subcategoria nomeada) ou pode ser um tipo genérico de cerveja (por exemplo, porter, brown ale). Se uma madeira incomum tiver sido usada, o participante deve fornecer uma breve descrição dos aspectos sensoriais que a madeira adiciona à cerveja. ";
$styles_entry_text_33B = "O participante deve especificar o caractere adicional de álcool, com informações sobre o barril se relevante para o perfil final. O participante deve especificar o estilo base; o estilo base pode ser um estilo clássico do BJCP (ou seja, uma subcategoria denominada ) ou pode ser um tipo genérico de cerveja (por exemplo, porter, brown ale). Se uma madeira ou ingrediente incomum tiver sido usado, o participante deve fornecer uma breve descrição dos aspectos sensoriais que os ingredientes acrescentam à cerveja. ";
$styles_entry_text_34A = "O participante deve especificar o nome da cerveja comercial que está sendo clonada, as especificações (estatísticas vitais) da cerveja e uma breve descrição sensorial ou uma lista de ingredientes usados ​​na fabricação da cerveja. Sem essa informação, os juízes não familiarizado com a cerveja não terá base para comparação. ";
$styles_entry_text_34B = "O participante deve especificar os estilos sendo misturados. O participante pode fornecer uma descrição adicional do perfil sensorial da cerveja ou as estatísticas vitais da cerveja resultante.";
$styles_entry_text_34C = "O participante deve especificar a natureza especial da cerveja experimental, incluindo os ingredientes ou processos especiais que a tornam inadequada em outras partes das diretrizes. O participante deve fornecer estatísticas vitais para a cerveja e uma breve descrição sensorial ou uma lista de ingredientes usados ​​na fabricação da cerveja Sem essa informação, os juízes não terão base para comparação. ";
$styles_entry_text_M1A = "Instruções da amostra: Os participantes devem especificar o nível de carbonatação e força. A doçura é considerada como SECA nesta categoria. Os participantes podem especificar variedades de mel.";
$styles_entry_text_M1B = "Os participantes devem especificar o nível de carbonatação e força. A doçura é considerada SEMI-DOCE nesta categoria. Os participantes podem especificar variedades de mel.";
$styles_entry_text_M1C = "Os participantes DEVEM especificar o nível e a força de carbonatação. A doçura é considerada DOCE nesta categoria. Os participantes PODEM especificar variedades de mel.";
$styles_entry_text_M2A = "Os participantes devem especificar o nível de carbonatação, força e doçura. Os participantes podem especificar variedades de mel. Os participantes podem especificar as variedades de maçã usadas; se especificado, um caráter varietal será esperado. Produtos com uma proporção relativamente baixa de mel são melhores introduzido como uma sidra especializada. Um ciser temperado deve ser introduzido como um Fruit Mice e Spice Mead. Um cyser com outras frutas deve ser inserido como um Melomel. Um cyser com ingredientes adicionais deve ser inserido como um hidromel Experimental. ";
$styles_entry_text_M2B = "Os participantes devem especificar o nível de carbonatação, força e doçura. Os participantes podem especificar variedades de mel. Os participantes podem especificar as variedades de uva usadas; se especificado, um caráter varietal será esperado. Um pyeap temperado (hippocras) deve ser inserido como Um Moinho de Frutas e Especiarias. Um picmento feito com outras frutas deve ser inserido como Melomel. Um picmento com outros ingredientes deve ser inserido como um Mead Experimental. ";
$styles_entry_text_M2C = "Os participantes devem especificar o nível de carbonatação, força e doçura. Os participantes podem especificar variedades de mel. Os participantes devem especificar as variedades de frutas usadas. Um hidromel feito com frutas silvestres e não silvestres (incluindo maçãs e uvas) deve ser digitado. Um melomel. Um hidromel que seja condimentado deve ser introduzido como um Fruit Mice and Spice Mead. Um hidromel que contenha outros ingredientes deve ser inserido como um Mead Experimental. ";
$styles_entry_text_M2D = "Os participantes devem especificar o nível de carbonatação, força e doçura. Os participantes podem especificar variedades de mel. Os participantes devem especificar as variedades de frutas usadas. Um hidromel com especiarias deve ser inserido como um Fruit and Spice Mead. O hidromel que contiver frutos não maduros deve ser introduzido como Melomel. Um hidromel com frutos de casca rija que contenha outros ingredientes deve ser introduzido como um hidromel experimental. ";
$styles_entry_text_M2E = "Os participantes devem especificar o nível de carbonatação, força e doçura. Os participantes podem especificar variedades de mel. Os participantes devem especificar as variedades de frutas usadas. Um melomel que é temperado deve ser inserido como um Fruit Mice. deve ser inserido como um Mead Experimental Melomels feitos com maçãs ou uvas como a única fonte de frutas devem ser inseridos como Cysers e Pyments, respectivamente. Melomels com maçãs ou uvas, além de outras frutas devem ser inseridos nesta categoria, não Experimental.";
$styles_entry_text_M3A = "Os participantes devem especificar o nível de carbonatação, força e doçura. Os participantes podem especificar variedades de mel. Os participantes devem especificar os tipos de especiarias usadas (embora misturas de especiarias conhecidas possam ser referidas pelo nome comum, Os participantes devem especificar os tipos de frutos utilizados: Se forem utilizadas apenas combinações de especiarias, introduza como Condimento, Erva ou Molho de Legumes. Se forem utilizadas apenas combinações de frutos, entre como Melomel, se forem utilizados outros tipos de ingredientes. , insira como um Mead Experimental. ";
$styles_entry_text_M3B = "Os participantes DEVEM especificar o nível de carbonatação, força e doçura. Os participantes DEVEM especificar variedades de mel. Os participantes DEVEM especificar os tipos de especiarias usados ​​(embora misturas de especiarias conhecidas possam ser referidas pelo nome comum, como especiarias de torta de maçã) . ";
$styles_entry_text_M4A = "Os participantes DEVEM especificar o nível de carbonatação, força e doçura. Os participantes PODEM especificar variedades de mel. Os participantes podem especificar o estilo básico ou cerveja ou tipos de malte usados. Produtos com uma proporção relativamente baixa de mel devem ser inseridos na Cerveja Condicionada. categoria como uma cerveja de mel. ";
$styles_entry_text_M4B = "Os participantes DEVEM especificar o nível de carbonatação, força e doçura. Os participantes PODEM especificar variedades de mel. Os participantes DEVEM especificar a natureza especial do hidromel, fornecendo uma descrição do hidromel para juízes se tal descrição não estiver disponível no BJCP." ;
$styles_entry_text_M4C = "Os participantes DEVEM especificar o nível de carbonatação, força e doçura. Os participantes DEVEM especificar variedades de mel. Os participantes DEVEM especificar a natureza especial do hidromel, seja uma combinação de estilos existentes, um hidromel experimental ou alguma outra criação. ingredientes especiais que dão um caráter identificável PODEM ser declarados. ";
$styles_entry_text_C1E = "Os participantes DEVEM especificar o nível de carbonatação (3 níveis). Os participantes DEVEM especificar a doçura (5 categorias). Os participantes DEVEM indicar a variedade de pêra usada (s).";
$styles_entry_text_C2A = "Os participantes DEVEM especificar se a sidra foi fermentada com barril ou envelhecida. Os participantes DEVEM especificar o nível de carbonatação (3 níveis). Os participantes DEVEM especificar a doçura (5 níveis).";
$styles_entry_text_C2B = "Os participantes DEVEM especificar o nível de carbonatação (3 níveis). Os participantes DEVEM especificar a doçura (5 categorias). Os participantes DEVEM especificar todos os frutos e / ou suco (s) de frutas adicionados.";
$styles_entry_text_C2C = "Os participantes devem especificar o nível de carbonatação (3 níveis). Os participantes devem especificar doçura (5 níveis).";
$styles_entry_text_C2D = "Os participantes DEVEM especificar a gravidade inicial, a gravidade final ou o açúcar residual e o nível de álcool. Os participantes DEVEM especificar o nível de carbonatação (3 níveis).";
$styles_entry_text_C2E = "Os participantes DEVEM especificar o nível de carbonatação (3 níveis). Os participantes DEVEM especificar doçura (5 categorias). Os participantes DEVEM especificar todos os ingredientes adicionados. Se o lúpulo for usado, o participante deve especificar a variedade / variedades usadas.";
$styles_entry_text_C2F = "Os participantes DEVEM especificar todos os ingredientes. Os participantes DEVEM especificar o nível de carbonatação (3 níveis). Os participantes DEVEM especificar a doçura (5 categorias).";

$user_text_000 = "Um novo endereço de e-mail é obrigatório e deve estar em um formulário válido.";
$user_text_001 = "Digite a senha antiga.";
$user_text_002 = "Digite a nova senha.";
$user_text_003 = "Por favor, marque esta caixa se você deseja mudar o seu endereço de e-mail.";

$volunteers_text_000 = "Se você se registrou,";
$volunteers_text_001 = "e depois escolha <em> Editar conta </ ​​em> no menu Minha conta indicado pelo";
$volunteers_text_002 = "ícone no menu superior";
$volunteers_text_003 = "e";
$volunteers_text_004 = "Se você tiver <em> não </em> registrado e estiver disposto a ser um juiz ou mordomo, registre-se";
$volunteers_text_005 = "Desde que você já se registrou,";
$volunteers_text_006 = "acessar sua conta";
$volunteers_text_007 = "para ver se você se ofereceu para ser um juiz ou mordomo";
$volunteers_text_008 = "Se você estiver disposto a julgar ou administrar, por favor, retorne ao cadastro em ou após";

$volunteers_text_009 = "Se você gostaria de se voluntariar para ser um membro da equipe de competição, registre ou atualize sua conta para indicar que você deseja fazer parte da equipe da competição.";
$volunteers_text_010 = "";

$login_text_000 = "Você já está logado.";
$login_text_001 = "Não há endereço de e-mail no sistema que corresponda ao que você digitou.";
$login_text_002 = "Tente de novo?";
$login_text_003 = "Você já registrou sua conta?";
$login_text_004 = "Esqueceu sua senha?";
$login_text_005 = "Redefini-lo";
$login_text_006 = "Para redefinir sua senha, digite o endereço de e-mail que você usou quando se registrou.";
$login_text_007 = "Verificar";
$login_text_008 = "Gerado aleatoriamente.";
$login_text_009 = "<strong> Indisponível. </ strong> Sua conta foi criada por um administrador e sua &quot; resposta secreta&quot; foi gerada aleatoriamente. Entre em contato com o administrador de um site para recuperar ou alterar sua senha.";
$login_text_010 = "Ou use a opção de e-mail abaixo.";
$login_text_011 = "Sua pergunta de segurança é ...";
$login_text_012 = "Se você não recebeu o email,";
$login_text_013 = "Um e-mail será enviado para você com sua pergunta e resposta de verificação. Certifique-se de verificar sua pasta de SPAM.";
$login_text_014 = "clique aqui para reenviá-lo para";
$login_text_015 = "Se você não consegue lembrar a resposta à sua pergunta de segurança, entre em contato com um oficial de competição ou administrador do site.";
$login_text_016 = "Envie-o por e-mail para";

$winners_text_000 = "Nenhum ganhador foi inserido para esta tabela. Por favor, volte mais tarde.";
$winners_text_001 = "Amostras vencedoras ainda não foram postadas. Por favor, volte mais tarde.";
$winners_text_002 = "Sua estrutura de premiação escolhida é premiar lugares por tabela. Selecione os lugares de premiação para a tabela como um todo abaixo.";
$winners_text_003 = "Sua estrutura de premiação escolhida é premiar lugares por categoria. Selecione os locais de premiação para cada categoria geral abaixo (pode haver mais de um nesta tabela).";
$winners_text_004 = "Sua estrutura de premiação escolhida é premiar lugares por subcategoria. Selecione os locais de premiação para cada subcategoria abaixo (pode haver mais de um nesta tabela).";

$output_text_000 = "Obrigado por participar da nossa competição";
$output_text_001 = "Um resumo de suas amostras, pontuações e lugares está abaixo.";
$output_text_002 = "Resumo para";
$output_text_003 = "amostras foram julgadas";
$output_text_004 = "Suas planilhas não podem ser geradas adequadamente. Entre em contato com os organizadores da competição.";
$output_text_005 = "Nenhuma atribuição de juiz / mordomo foi definida";
$output_text_006 = "para esta localização";
$output_text_007 = "Se você quiser imprimir cartões de mesa em branco, feche esta janela e escolha <em> Imprimir Cartões de Mesa: Todas as Tabelas </em> no menu <em> Relatório </em>.";
$output_text_008 = "Por favor, certifique-se de verificar se o seu Juiz BJCP ID está correto. Se não for, ou se você tiver um e não estiver listado, por favor insira-o no formulário.";
$output_text_009 = "Se o seu nome não estiver na lista abaixo, entre na (s) folha (s) anexada (s).";
$output_text_010 = "Para receber o crédito de julgamento, por favor, certifique-se de digitar seu ID de Juiz BJCP de forma correta e legível.";
$output_text_011 = "Nenhuma tarefa foi feita.";
$output_text_012 = "Total de amostras neste local";
$output_text_013 = "Nenhum participante forneceu anotações aos organizadores.";
$output_text_014 = "A seguir estão as notas para os organizadores inseridos pelos juízes.";
$output_text_015 = "Nenhum participante forneceu anotações aos organizadores.";
$output_text_016 = "Inventário de amostras pós-julgamento";
$output_text_017 = "Se não houver amostras abaixo, os voos desta tabela não foram atribuídos a rodadas.";
$output_text_018 = "Se houver amostras faltando, todas as amostras não foram atribuídas a um vôo ou rodada OU foram designadas para uma rodada diferente.";
$output_text_019 = "Se não houver amostras abaixo, esta tabela não foi atribuída a uma rodada.";
$output_text_020 = "Nenhuma amostra é elegível.";
$output_text_021 = "Folha de referência do número de amostra/número de julgamento";
$output_text_022 = "Os pontos neste relatório são derivados dos requisitos oficiais da Competição sancionada pelo BJCP, disponíveis em";
$output_text_023 = "inclui Melhor do Show";
$output_text_024 = "Relatório de Pontos BJCP";
$output_text_025 = "Total de Pontos de Pessoal Disponíveis";
$output_text_026 = "Estilos nesta categoria não são aceitos nesta competição.";
$output_text_027 = "link para Diretrizes de Estilo do Programa de Certificação de Juiz de Cerveja";
$output_text_028 = "";
$output_text_029 = "";
$output_text_030 = "";

$maintenance_text_000 = "O administrador do site retirou o site para manutenção.";
$maintenance_text_001 = "Por favor, volte mais tarde.";

/**
 * ------------------------------------------------------------------------
 * Version 2.1.10-2.1.12 Additions
 * ------------------------------------------------------------------------
 */

$label_entry_numbers = "Número(s) de amostra"; // Para PayPal IPN Email
$label_status = "Status"; // Para PayPal IPN Email
$label_amount = "Valor"; // Para PayPal IPN Email
$label_transaction_id = "ID da transação"; // Para PayPal IPN Email
$label_organization = "Organização";
$label_ttb = "Número TTB";
$label_username = "Nome de usuário";
$label_from = "De"; // Para cabeçalhos de email
$label_to = "Para"; // Para cabeçalhos de email
$label_varies = "Varia";
$label_styles_accepted = "Estilos aceitos";
$label_judging_styles = "Julgando Estilos";
$label_select_club = "Selecione ou pesquise seu clube";
$label_select_style = "Selecione ou pesquise o estilo da sua amostra";
$label_select_country = "Selecione ou pesquise seu país";
$label_select_dropoff = "Selecione seu local de entrega";
$label_club_enter = "Digite o nome do clube";
$label_secret_05 = "Qual é o nome de solteira de sua avó materna?";
$label_secret_06 = "Qual foi o primeiro nome da sua primeira namorada ou namorado?";
$label_secret_07 = "Qual foi a marca e modelo do seu primeiro veículo?";
$label_secret_08 = "Qual foi o sobrenome do seu professor da terceira série?";
$label_secret_09 = "Em que cidade ou cidade você conheceu seu outro significativo?";
$label_secret_10 = "Qual foi o primeiro nome do seu melhor amigo no sexto ano?";
$label_secret_11 = "Qual é o nome do seu artista ou grupo musical favorito?";
$label_secret_12 = "Qual foi seu apelido de infância?";
$label_secret_13 = "Qual é o sobrenome do professor que lhe deu sua primeira nota com falha?";
$label_secret_14 = "Qual é o nome do seu amigo de infância favorito?";
$label_secret_15 = "Em que cidade ou cidade sua mãe e seu pai se conheceram?";
$label_secret_16 = "Qual foi o número de telefone de infância que você mais lembra, incluindo código de área?";
$label_secret_17 = "Qual foi seu lugar favorito para visitar quando criança?";
$label_secret_18 = "Onde você estava quando teve seu primeiro beijo?";
$label_secret_19 = "Em que cidade ou cidade foi seu primeiro emprego?";
$label_secret_20 = "Em que cidade ou cidade você estava no Ano Novo de 2000?";
$label_secret_21 = "Qual é o nome de uma faculdade em que você se inscreveu, mas não compareceu?";
$label_secret_22 = "Qual é o primeiro nome do menino ou menina que você beijou pela primeira vez?";
$label_secret_23 = "Qual foi o nome do seu primeiro bicho de pelúcia, boneco ou boneco de ação?";
$label_secret_24 = "Em que cidade ou cidade você conheceu seu cônjuge / outro significativo?";
$label_secret_25 = "Em que rua você mora na primeira série?";
$label_secret_26 = "Qual é a velocidade da velocidade do ar de uma carga sem carga?";
$label_secret_27 = "Qual é o nome do seu programa de TV favorito cancelado?";
$label_pro = "Profissional";
$label_amateur = "Amador";
$label_hosted = "Hospedado";
$label_edition = "Edição";
$label_pro_comp_edition = "Edição profissional da competição";
$label_amateur_comp_edition = "Edição de competição amadora";
$label_optional_info = "Informação Opcional";
$label_or = "Ou";
$label_admin_payments = "Pagamentos";
$label_payer = "Pagador";
$label_pay_with_paypal = "Pague com PayPal";
$label_submit = "Enviar";
$label_id_verification_question = "Pergunta de Verificação de ID";
$label_id_verification_answer = "Resposta de verificação de ID";
$label_server = "Servidor";
$label_password_reset = "Redefinição de senha";
$label_id_verification_request = "Pedido de verificação de ID";
$label_new_password = "Nova senha";
$label_confirm_password = "Confirmar senha";
$label_with_token = "Com token";
$label_password_strength = "Força da senha";
$label_entry_shipping = "Envio por correios";
$label_jump_to = "Ir para ...";
$label_top = "Top";

$label_bjcp_cider = "Juiz de Cidra";

$header_text_112 = "Você não tem privilégios de acesso suficientes para executar esta ação.";
$header_text_113 = "Você pode editar apenas as informações da sua conta.";
$header_text_114 = "Como administrador, você pode alterar as informações da conta de um usuário via Admin> Entradas e Participantes> Gerenciar Participantes.";
$header_text_115 = "Resultados foram publicados.";
$header_text_116 = "Se você não receber o email dentro de um prazo razoável, entre em contato com um oficial da competição ou administrador do site para redefinir sua senha para você.";

$alert_text_082 = "Desde que você se inscreveu como juiz ou assistente, você não tem permissão para adicionar amostras à sua conta. Apenas representantes de uma organização podem adicionar amostras às suas contas.";
$alert_text_083 = "Adicionar e editar amostras não está disponível.";
$alert_text_084 = "Como dministrador, você pode adicionar uma amostra à conta de uma organização usando o menu suspenso & quot; Adicionar amostra para ...&quot; na página Administração &gt; Entradas e participantes &gt; Gerenciar inscrições.";
$alert_text_085 = "Você não conseguirá imprimir a papelada de qualquer amostra (etiquetas de garrafas, etc.) até que o pagamento seja confirmado e tenha sido marcado como & quot; pago & quot; abaixo.";

$brew_text_027 = "Este estilo da Associação de Cervejeiros requer uma declaração do fabricante de cerveja sobre a natureza especial do produto. Veja o <a href=\"https://www.brewersassociation.org/resources/brewers-association-beer-style- guidelines / \"target = \" _ blank \">Orientações de Estilo BA </a> para orientação específica.";
$brew_text_028 = "*** NÃO REQUERIDO *** Adicione informações detalhadas nas diretrizes de estilo como uma característica que você PODE declarar.";
$brew_text_029 = "Edição do administrador desativada. Seu perfil é considerado um perfil pessoal e não um perfil organizacional e, portanto, não está qualificado para adicionar amostras. Para adicionar uma amostra a uma organização, acesse a lista Gerenciar inscrições e escolha uma organização em;Adicionar uma amostra para...&quot; dropdown.";

$brewer_text_022 = "Você será capaz de identificar um co-cervejeiro ao adicionar suas amostras.";
$brewer_text_023 = "Selecione <strong>None</strong> se você não é afiliado a um clube ou associação. Selecione <strong>Other</strong> se seu clube não estiver na lista - <strong> não deixe de usar a caixa de pesquisa </strong>.";
$brewer_text_024 = "Por favor, forneça seu primeiro nome.";
$brewer_text_025 = "Por favor, forneça seu sobrenome.";
$brewer_text_026 = "Por favor, forneça seu número de telefone.";
$brewer_text_027 = "Por favor, forneça seu endereço.";
$brewer_text_028 = "Por favor, forneça sua cidade.";
$brewer_text_029 = "Por favor, forneça seu estado ou província.";
$brewer_text_030 = "Por favor, forneça o seu CEP ou código postal.";
$brewer_text_031 = "Por favor, escolha o seu país.";
$brewer_text_032 = "Por favor, forneça o nome da sua organização.";
$brewer_text_033 = "Por favor, forneça uma pergunta de segurança.";
$brewer_text_034 = "Por favor, forneça uma resposta à sua pergunta de segurança.";
$brewer_text_035 = "Você passou no exame BJCP de Juiz de Cidra?";

$entry_info_text_047 = "Se o nome de um estilo contém um link, ele possui requisitos específicos para a amostra. Clique ou toque no nome para acessar os estilos da Associação de Cervejeiros conforme listado em seu site.";

$brewer_entries_text_016 = "Estilo digitado NÃO aceito";
$brewer_entries_text_017 = "As inscrições não serão exibidas como recebidas até que a equipe da competição as tenha marcado como tal no sistema. Normalmente, isso ocorre DEPOIS todas as amostras foram coletadas de todos os locais de entrega e classificadas.";
$brewer_entries_text_018 = "Você não poderá imprimir a papelada desta amostra (etiquetas de garrafas, etc.) até que seja marcada como paga.";
$brewer_entries_text_019 = "A impressão de documentos da amostra não está disponível no momento.";
$brewer_entries_text_020 = "A edição das inscrições não está disponível no momento. Se você deseja editar sua inscrição, entre em contato com um oficial da competição.";

if (SINGLE) $brewer_info_000 = "Olá";
else $brewer_info_000 = "Obrigado por participar do";
$brewer_info_001 = "Os detalhes da sua conta foram atualizados pela última vez";
$brewer_info_002 = "Reserve um momento para <a href=\"#entries\"> revisar suas amostras</a>";
$brewer_info_003 = "pague as taxas de inscrição</a>";
$brewer_info_004 = "por amostra";
$brewer_info_005 = "É necessária uma afiliação à American Homebrewers Association (AHA) se uma de suas inscrições for selecionada para um Grande Festival Americano de Cerveja Pro-Am.";
$brewer_info_006 = "Imprima etiquetas de envio para anexar à sua caixa (s) de garrafas.";
$brewer_info_007 = "Imprimir etiquetas de remessa";
$brewer_info_008 = "Você já foi atribuído a uma tabela como";
$brewer_info_009 = "Se você deseja alterar sua disponibilidade e / ou retirar sua função, contate o organizador da competição ou o coordenador do juiz.";
$brewer_info_010 = "Você já foi designado como";
$brewer_info_011 = "ou";
$brewer_info_012 = "Imprima suas etiquetas de avaliação de pontuação";

$pay_text_030 = "Ao clicar no &quot;Entendo; botão a baixo, você será direcionado para o PayPal para efetuar seu pagamento. Depois de ter <strong> concluído </ strong> o seu pagamento, o PayPal o redirecionará para este site e envie um recibo por e-mail para a transação. <strong> Se o pagamento foi bem-sucedido, seu status pago será atualizado automaticamente. É possível que você precise aguardar alguns minutos para que o status do pagamento seja atualizado. </ strong> atualize a página de pagamento ou acesse sua lista de amostras. ";
$pay_text_031 = "Sobre sair deste site";
$pay_text_032 = "Nenhum pagamento é necessário. Obrigado!";
$pay_text_033 = "Você tem amostras não pagas. Clique para pagar suas amostras.";

$register_text_035 = "As informações que você fornece além do nome da sua organização são estritamente para fins de registro e manutenção.";
$register_text_036 = "Uma condição para entrar na competição é fornecer essas informações, incluindo o endereço de e-mail e o número de telefone de uma pessoa de contato. O nome da sua organização pode ser exibido se uma das suas inscrições for publicada.";
$register_text_037 = "Confirmação de inscrição";
$register_text_038 = "Um administrador registrou você para uma conta. O seguinte é a confirmação da entrada de informação:";
$register_text_039 = "Obrigado por registrar uma conta. A confirmação a seguir é da informação que você forneceu:";
$register_text_040 = "Se alguma das informações acima estiver incorreta,";
$register_text_041 = "faça login na sua conta";
$register_text_042 = "e faça as alterações necessárias. Boa sorte na competição!";
$register_text_043 = "Por favor, não responda a este e-mail, pois ele é gerado automaticamente. A conta de origem não está ativa nem monitorada.";
$register_text_044 = "Por favor, forneça um nome de organização.";
$register_text_045 = "Forneça um nome para a cervejaria, nome do brewpub, etc. Certifique-se de verificar as informações da competição para tipos de bebidas aceitos.";
$register_text_046 = "Apenas para organizações dos EUA.";

$user_text_004 = "Certifique-se de usar letras maiúsculas e minúsculas, números e caracteres especiais para uma senha mais forte.";
$user_text_005 = "Seu endereço de e-mail atual é";

$login_text_017 = "Envie-me minha resposta da pergunta de segurança";
$login_text_018 = "Seu nome de usuário (endereço de email) é obrigatório.";
$login_text_019 = "Sua senha é necessária.";
$login_text_020 = "O token fornecido é inválido ou já foi usado. Por favor, use a função de senha esquecida novamente para gerar um novo token de redefinição de senha.";
$login_text_021 = "O token fornecido expirou. Por favor, use a função de senha esquecida novamente para gerar um novo token de redefinição de senha.";
$login_text_022 = "O e-mail que você digitou não está associado ao token fornecido. Por favor, tente novamente.";
$login_text_023 = "As senhas não combinam. Por favor, tente novamente.";
$login_text_024 = "É necessária uma senha de confirmação.";
$login_text_025 = "Esqueceu sua senha?";
$login_text_026 = "Digite o endereço de e-mail da sua conta e a nova senha abaixo.";
$login_text_027 = "Sua senha foi reiniciada com sucesso. Agora você pode entrar com a nova senha.";

$winners_text_005 = "O (s) vencedor (es) da Best of Show ainda não foi publicado. Por favor, volte mais tarde.";

$paypal_response_text_000 = "Seu pagamento foi concluído. Os detalhes da transação são fornecidos aqui para sua conveniência.";
$paypal_response_text_001 = "Por favor, note que você receberá uma comunicação oficial do PayPal no endereço de e-mail listado abaixo.";
$paypal_response_text_002 = "Boa sorte na competição!";
$paypal_response_text_003 = "Por favor, não responda a este e-mail, pois ele é gerado automaticamente. A conta de origem não está ativa nem monitorada.";
$paypal_response_text_004 = "O PayPal processou sua transação.";
$paypal_response_text_005 = "O status do seu pagamento pelo PayPal é:";
$paypal_response_text_006 = "A resposta do Paypal foi inválida. & quot; Por favor, tente efetuar o seu pagamento novamente. ";
$paypal_response_text_007 = "Por favor, entre em contato com o organizador da competição se você tiver alguma dúvida.";
$paypal_response_text_008 = "Pagamento PayPal inválido";
$paypal_response_text_009 = "Detalhes do pagamento via PayPal";

$pwd_email_reset_text_000 = "Foi feita uma solicitação para verificar a conta no";
$pwd_email_reset_text_001 = "website usando a função de e-mail Verification ID. Se você não iniciou isto, entre em contato com o organizador da competição.";
$pwd_email_reset_text_002 = "A resposta de verificação de ID faz distinção entre maiúsculas e minúsculas";
$pwd_email_reset_text_003 = "Foi feita uma solicitação para alterar sua senha no";
$pwd_email_reset_text_004 = "website. Se você não iniciou isto, não se preocupe. Sua senha não pode ser redefinida sem o link abaixo.";
$pwd_email_reset_text_005 = "Para redefinir sua senha, clique no link abaixo ou copie / cole no seu navegador.";

$best_brewer_text_000 = "cervejeiros participantes";
$best_brewer_text_001 = "HM";
$best_brewer_text_002 = "Os desempatadores foram aplicados de acordo com a <a href=\"#\" data-toggle=\"modal\" data-target=\"#scoreMethod\"> metodologia de pontuação </a>. ";
$best_brewer_text_003 = "Metodologia de pontuação";
$best_brewer_text_004 = "Cada amostra de colocação recebe os seguintes pontos:";
$best_brewer_text_005 = "Os seguintes desempatadores foram aplicados, em ordem de prioridade:";
$best_brewer_text_006 = "O maior número total de primeiro, segundo e terceiro lugares.";
$best_brewer_text_007 = "O maior número total de lugares de primeira, segunda, terceira, quarta e menção honrosa.";
$best_brewer_text_008 = "O maior número de primeiros lugares.";
$best_brewer_text_009 = "O menor número de amostras.";
$best_brewer_text_010 = "A pontuação mínima mais alta.";
$best_brewer_text_011 = "A maior pontuação máxima.";
$best_brewer_text_012 = "A pontuação média mais alta.";
$best_brewer_text_013 = "Não utilizado.";
$best_brewer_text_014 = "clubes participantes";

$dropoff_qualifier_text_001 = "Por favor, preste atenção nas notas fornecidas para cada local de entrega, pois podem haver especificidades, tais como, diferentes prazos, horários e pessoas específicas para deixar as amostras. <strong class=\"text-danger\">Os participantes são responsáveis por ler as informações fornecidas pelos organizadores para cada local de entrega.</strong>";

$brewer_text_036 = "Como você escolheu <em> Outros</em>, verifique se o clube que você ingressou não está na nossa lista de forma semelhante.";
$brewer_text_037 = "Por exemplo, você pode ter entrado a sigla do seu clube em vez do nome completo.";
$brewer_text_038 = "Nomes de clubes consistentes entre os usuários são essenciais para os cálculos do \"Melhor Clube\", se implementados para esta competição.";
$brewer_text_039 = "O clube que você entrou anteriormente não corresponde a um da nossa lista.";
$brewer_text_040 = "Por favor, escolha da lista ou escolha <em> Outro </em> e digite o nome do seu clube.";

/**
 * ------------------------------------------------------------------------
 * Version 2.1.13 Additions
 * ------------------------------------------------------------------------
 */

$entry_info_text_048 = "Para garantir um julgamento adequado, o participante deve fornecer informações adicionais sobre a bebida.";
$entry_info_text_049 = "Para garantir um julgamento adequado, o participante deve fornecer o nível de força da bebida.";
$entry_info_text_050 = "Para garantir um julgamento adequado, o participante deve fornecer o nível de carbonatação da bebida.";
$entry_info_text_051 = "Para garantir um julgamento adequado, o participante deve fornecer o nível de doçura da bebida.";
$entry_info_text_052 = "Ao entrar nesta categoria, o participante deve fornecer mais informações para que a entrada seja julgada com precisão. Quanto mais informação, melhor.";

$output_text_028 = "As seguintes entradas têm possíveis alérgenos - como entrada pelos participantes.";
$output_text_029 = "Nenhum participante forneceu informações sobre alérgenos para suas inscrições.";

$label_this_style = "Esse Estilo";
$label_notes = "Notas";
$label_possible_allergens = "Possíveis Alérgenos";
$label_please_choose = "Por favor Escolha";
$label_mead_cider_info = "Mead/Cider Info";

/**
 * ------------------------------------------------------------------------
 * Version 2.1.10-2.1.14 Additions
 * ------------------------------------------------------------------------
 */

$label_winners = "Vencedores";
$label_unconfirmed_entries = "Entradas não Confirmadas";
$label_recipe = "Receita";
$label_view = "Visão";
$label_number_bottles = "Número de Garrafas Necessárias por Entrada";
$label_pro_am = "Pro-Am";

$pay_text_034 = "O limite de entradas pagas foi atingido - pagamentos adicionais não estão sendo aceitos.";

$bottle_labels_000 = "Os rótulos não podem ser gerados no momento.";
$bottle_labels_001 = "Certifique-se de verificar as regras de aceitação de participação na competição para diretrizes específicas de fixação de rótulos antes de enviar!";
$bottle_labels_002 = "Tipicamente, é usada fita de embalagem transparente para fixar ao barril de cada entrada - cobrir completamente a etiqueta.";
$bottle_labels_003 = "Normalmente, uma faixa de borracha é usada para fixar etiquetas em cada entrada.";
if (isset($_SESSION['jPrefsBottleNum'])) $bottle_labels_004 = "Observação: são fornecidas 4 etiquetas como cortesia. Esta competição requer ".$_SESSION['jPrefsBottleNum']." garrafas por inscrição. Descarte qualquer etiqueta extra.";
else $bottle_labels_004 = "Observação: são fornecidas 4 etiquetas como cortesia. Descarte qualquer etiqueta extra.";
$bottle_labels_005 = "Se algum item estiver faltando, feche esta janela e edite a entrada.";
$bottle_labels_006 = "Espaço reservado para uso do pessoal da competição.";
$bottle_labels_007 = "ESTE FORMULÁRIO DE RECEITA É APENAS PARA SEUS REGISTROS - NÃO inclua uma cópia dele na remessa de entrada.";

$brew_text_040 = "Não há necessidade de especificar o glúten como alérgeno para qualquer estilo de cerveja; supõe-se que estará presente. As cervejas sem glúten devem ser inseridas na categoria Cerveja sem glúten (BA) ou na categoria Cerveja alternativa de grãos (BJCP). Apenas especifique o glúten como alérgeno nos estilos de hidromel ou sidra se uma fonte fermentável contiver glúten (por exemplo, malte de cevada, trigo ou centeio) ou se o fermento de cerveja foi usado.";

$brewer_text_041 = "Você já recebeu a oportunidade Pro-Am de competir na próxima competição Pro-Am do Great American Beer Festival?";
$brewer_text_042 = "Se você já recebeu um Pro-Am ou já fez parte da equipe de cervejarias de qualquer cervejaria, indique-o aqui. Isso ajudará a equipe da competição e os representantes da cervejaria Pro-Am (se aplicável a esta competição) a escolher as entradas Pro-Am de cervejeiros que não possuem uma.";

/**
 * ------------------------------------------------------------------------
 * Version 2.1.15 Additions
 * ------------------------------------------------------------------------
 */

$label_submitting = "Enviando";
$label_additional_info = "Entradas com informações adicionais";
$label_working = "Trabalhando";

$output_text_030 = "Por favor espere.";

$brewer_entries_text_021 = "Verifique as entradas para imprimir suas etiquetas de garrafa. Marque a caixa de seleção superior para marcar ou desmarcar todas as caixas na coluna.";
$brewer_entries_text_022 = "Imprimir Todas as Etiquetas de Garrafas para Entradas Verificadas";
$brewer_entries_text_023 = "Os rótulos dos frascos serão abertos em uma nova guia ou janela.";
$brewer_entries_text_024 = "Imprimir Etiquetas de Garrafa";

/**
 * ------------------------------------------------------------------------
 * Version 2.1.18 Additions
 * ------------------------------------------------------------------------
 */

$output_text_031 = "Pressione Esc para ocultar.";
$styles_entry_text_21X = "O participante DEVE especificar uma força (sessão: 3,0-5,0%, padrão: 5,0-7,5%, duplo: 7,5-9,5%).";
$styles_entry_text_PRX4 = "O participante deve especificar os tipos de frutas frescas usadas.";

/**
 * ------------------------------------------------------------------------
 * Version 2.1.19 Additions
 * ------------------------------------------------------------------------
 */

$output_text_032 = "A contagem de entradas reflete apenas os participantes que indicaram um local de entrega em seu perfil de conta. O número real de entradas pode ser maior ou menor.";
$brewer_text_043 = "Ou você está ou já trabalhou na equipe de cervejaria de qualquer cervejaria? Isso inclui posições de cervejeiro, bem como técnicos de laboratório, equipe de adega, equipe de engarrafamento / conservas, etc. Os funcionários atuais e antigos da equipe de cervejaria não são elegíveis para participar da competição Pro-Am do Great American Beer Festival.";
$label_entrant_reg = "Registro de Participantes";
$sidebar_text_026 = "estão no sistema a partir de";
$label_paid_entries = "Entradas Pagas";

/**
 * ------------------------------------------------------------------------
 * Version 2.2.0 Additions
 * Via Google Translate English to Portuguese - and for that, I'm sorry. :)
 * ------------------------------------------------------------------------
 */

$alert_text_086 = "O Internet Explorer não é compatível com o BCOE&M - recursos e funções não serão renderizados corretamente e sua experiência não será a ideal. Por favor, atualize para um navegador mais recente.";
$alert_text_087 = "Para uma experiência ideal e para que todos os recursos e funções sejam executados corretamente, habilite o JavaScript para continuar usando este site. Caso contrário, ocorrerá um comportamento inesperado.";
$alert_text_088 = "A apresentação dos prêmios estará disponível publicamente após a publicação dos resultados.";
$alert_text_089 = "Os dados arquivados não estão disponíveis.";

$brewer_entries_text_025 = "Imprimir folhas de pontuação dos juízes";

$brewer_info_013 = "Você foi designado como juiz.";
$brewer_info_014 = "Acesse o painel de julgamento usando o botão abaixo para inserir avaliações das entradas atribuídas a você.";

$contact_text_004 = "Os organizadores da competição não especificaram nenhum contato.";

$label_thank_you = "Obrigado";
$label_congrats_winners = "Parabéns a Todos os Vencedores de Medalhas";
$label_placing_entries = "Colocando Entradas";
$label_by_the_numbers = "Estatisticas";
$label_launch_pres = "Apresentação de Prêmios de Lançamento";
$label_entrants = "Competidores";

$label_judging_dashboard = "Painel de Julgamento";
$label_table_assignments = "Tarefas de Mesa";
$label_table = "Mesa";
$label_edit = "Editar";
$label_add = "Adicione";
$label_disabled = "Inválido";
$label_judging_scoresheet = "Folha de Pontuação de Julgamento";
$label_checklist_version = "Versão da Lista de Verificação";
$label_classic_version = "Versão Clássica";
$label_structured_version = "Versão Estruturada";
$label_submit_evaluation = "Enviar Avaliação";
$label_edit_evaluation = "Editar Avaliação";
$label_your_score = "Sua Pontuação";
$label_your_assigned_score = "Sua Pontuação de Consenso Inserida";
$label_assigned_score = "Pontuação de Consenso";
$label_accepted_score = "Pontuação Oficial Aceita";
$label_recorded_scores = "Pontuações de Consenso Inseridas";
$label_go = "Ir";
$label_go_back = "Volte";
$label_na = "N/A";
$label_evals_submitted = "Avaliações Enviadas";
$label_evaluations = "Avaliações de Entrada";
$label_submitted_by = "Enviado por";
$label_attention = "Atenção!";
$label_unassigned_eval = "Avaliações não Atribuídas";
$label_head_judge = "Juiz Mor";
$label_lead_judge = "Juiz Principal";
$label_mini_bos_judge = "Juiz Mini-BOS";
$label_view_my_eval = "Ver Minha Avaliação";
$label_view_other_judge_eval = "Ver a Avaliação de Outro Juiz";
$label_place_awarded = "Lugar concedido";
$label_honorable_mention = "Menção honrosa";
$label_places_awarded_table = "Lugares premiados nesta mesa";
$label_places_awarded_duplicate = "Lugares duplicados concedidos nesta mesa";

$evaluation_info_000 = "O pool de entrada para cada uma das tabelas e voos que foram atribuídos a você é detalhado abaixo.";
$evaluation_info_001 = "Esta competição está empregando julgamento em fila. Se houver mais de um par de juízes em sua mesa, avalie a próxima entrada na fila estabelecida.";
$evaluation_info_002 = "Para garantir uma competição precisa e tranquila, você e seu (s) parceiro (s) de juízes SÓ devem julgar as inscrições em sua mesa que ainda não foram avaliadas. Consulte seu organizador ou coordenador de juízes se tiver alguma dúvida.";
$evaluation_info_003 = "Aguardando aceitação final de um administrador do site.";
$evaluation_info_004 = "Sua pontuação de consenso foi inserida.";
$evaluation_info_005 = "Esta entrada não faz parte do seu voo atribuído.";
$evaluation_info_006 = "Edite conforme necessário.";
$evaluation_info_007 = "Escolha uma das seguintes entradas nesta tabela.";
$evaluation_info_008 = "Para registrar sua avaliação, selecione o botão Adicionar correspondente de uma entrada. Apenas as tabelas das sessões de julgamento anteriores e atuais estão disponíveis.";
$evaluation_info_009 = "Você foi designado como juiz, mas não foi designado para nenhuma mesa (s) ou vôo (s) no sistema. Por favor, verifique com o organizador ou coordenador do juiz.";
$evaluation_info_010 = "Esta entrada faz parte do seu voo atribuído.";
$evaluation_info_011 = "Adicione uma avaliação para uma entrada não atribuída a você.";
$evaluation_info_012 = "Use apenas quando for solicitado a avaliar uma entrada que não faz parte da tabela atribuída.";
$evaluation_info_013 = "A entrada não foi encontrada.";
$evaluation_info_014 = "Verifique o número de entrada de seis caracteres e tente novamente.";
$evaluation_info_015 = "Certifique-se de que o número tenha 6 dígitos.";
$evaluation_info_016 = "Nenhuma avaliação registrada.";
$evaluation_info_017 = "As pontuações de consenso inseridas pelos juízes não correspondem.";
$evaluation_info_018 = "A verificação é necessária para as seguintes entradas:";
$evaluation_info_019 = "As seguintes entradas têm apenas uma avaliação enviada:";
$evaluation_info_020 = "Seu painel de julgamento estará disponível"; // Punctuation omitted intentionally
$evaluation_info_021 = "para adicionar avaliações para entradas atribuídas a você"; // Punctuation omitted intentionally
$evaluation_info_022 = "O envio de julgamento e avaliação está encerrado.";
$evaluation_info_023 = "Se você tiver alguma dúvida, entre em contato com o organizador da competição ou coordenador de juízes.";
$evaluation_info_024 = "Você foi atribuído às seguintes tabelas. As listas de inscrições para cada mesa serão exibidas apenas para as sessões de julgamento anteriores e atuais.";
$evaluation_info_025 = "Juízes designados para esta mesa:";
$evaluation_info_026 = "Todas as pontuações de consenso inseridas pelos juízes coincidem.";
$evaluation_info_027 = "Entradas que você completou, ou que um administrador inseriu em seu nome, que não foram atribuídas a você no sistema.";
$evaluation_info_028 = "A sessão de julgamento terminou.";
$evaluation_info_029 = "Lugares duplicados foram atribuídos nas seguintes tabelas:";
$evaluation_info_030 = "Os administradores precisarão inserir as entradas de colocação que permanecerem.";
$evaluation_info_031 = "avaliações foram adicionadas pelos juízes";
$evaluation_info_032 = "Várias avaliações para uma única inscrição foram enviadas por um juiz.";
$evaluation_info_033 = "Embora seja uma ocorrência incomum, uma avaliação duplicada pode ser enviada devido a problemas de conectividade, etc. Uma única avaliação registrada para cada juiz deve ser oficialmente aceita - todas as outras devem ser excluídas para evitar confusão entre os participantes.";
$evaluation_info_034 = "Ao avaliar estilos de tipo especialidade, use esta linha para comentar sobre características exclusivas a ela, como frutas, especiarias, fermentáveis, acidez, etc.";
$evaluation_info_035 = "Faça comentários sobre estilo, receita, processo e prazer ao beber. Inclua sugestões úteis para o cervejeiro.";
if (isset($_SESSION['jPrefsScoreDispMax'])) $evaluation_info_036 = "Uma ou mais pontuações de juízes estão fora da faixa de pontuação aceitável. ".$_SESSION['jPrefsScoreDispMax']. " ou menos é o intervalo aceitável..";
$evaluation_info_037 = "Todos os voos nesta tabela parecem estar completos.";
$evaluation_info_038 = "Como Juiz Principal, é sua responsabilidade verificar se as pontuações de consenso de cada inscrição correspondem, certificar-se de que todas as pontuações dos juízes para cada inscrição estão dentro da faixa apropriada e atribuir lugares às inscrições designadas.";
$evaluation_info_039 = "Inscrições nesta mesa:";
$evaluation_info_040 = "Entradas pontuadas nesta mesa:";
$evaluation_info_041 = "Entradas pontuadas em seu voo:";
$evaluation_info_042 = "Suas entradas pontuadas:";
$evaluation_info_043 = "Juízes com avaliações nesta mesa:";

$label_submitted = "Submetido";
$label_ordinal_position = "Posição Ordinal em Voo";
$label_alcoholic = "Alcoólico";
$descr_alcoholic = "O aroma, sabor e efeito de aquecimento do etanol e álcoois superiores. Às vezes descrito como &quot;quente&quot;.";
$descr_alcoholic_mead = "O efeito do etanol. Aquecimento. Quente.";
$label_metallic = "Metálico"; 
$descr_metallic = "Sabor a estanho, moeda, cobre, ferro ou sangue.";
$label_oxidized = "Oxidado";
$descr_oxidized = "Qualquer um ou combinação de aromas e sabores rançosos, vinosos, de papelão, papel ou xerez. Desatualizado.";
$descr_oxidized_cider = "Envelhecimento, o aroma / sabor de xerez, passas ou frutas machucadas.";
$label_phenolic = "Fenólico";
$descr_phenolic = "Picante (cravo, pimenta), fumegante, plástico, fita adesiva de plástico e / ou medicinal (clorofenólico).";
$label_vegetal = "Vegetal";
$descr_vegetal = "Aroma e sabor de vegetais cozidos, enlatados ou podres (repolho, cebola, aipo, aspargos, etc.).";
$label_astringent = "Adstringente";
$descr_astringent = "Franzido, aspereza persistente e / ou secura no final / gosto residual; granulação severa; rouquidão.";
$descr_astringent_cider = "Uma sensação de secura na boca semelhante a mastigar um saquinho de chá. Deve estar em equilíbrio, se presente.";
$label_acetaldehyde = "Acetaldeído";
$descr_acetaldehyde = "Aroma e sabor de maçã verde.";
$label_diacetyl = "Diacetil";
$descr_diacetyl = "Aroma e sabor de manteiga artificial, caramelo ou caramelo. Às vezes percebido como uma mancha na língua.";
$descr_diacetyl_cider = "Aroma ou sabor de manteiga ou caramelo.";
$label_dms = "DMS (Dimetil Sulfeto)";
$descr_dms = "Em níveis baixos, um aroma e sabor doce, parecido com milho cozido ou enlatado.";
$label_estery = "Estery";
$descr_estery = "Aroma e / ou sabor de qualquer éster (frutas, aromas de frutas ou rosas).";
$label_grassy = "Gramíneo";
$descr_grassy = "Aroma / sabor a erva acabada de cortar ou a folhas verdes.";
$label_light_struck = "Atingido por luz";
$descr_light_struck = "Semelhante ao aroma de um gambá.";
$label_musty = "Mofado";
$descr_musty = "Aromas / sabores rançosos, mofados ou mofados.";
$label_solvent = "Solvente";
$descr_solvent = "Aromas e sabores de álcoois superiores (álcoois fusel). Semelhante a aromas de acetona ou diluente de laca.";
$label_sour_acidic = "Azedo/Ácido";
$descr_sour_acidic = "Acidez no aroma e sabor. Pode ser cortante e limpo (ácido láctico) ou semelhante ao vinagre (ácido acético).";
$label_sulfur = "Enxofre";
$descr_sulfur = "O aroma de ovos podres ou fósforos acesos.";
$label_sulfury = "Enxofre";
$label_yeasty = "Fermento";
$descr_yeasty = "Aroma ou sabor a pão, enxofre ou levedura.";
$label_acetified = "Acetificado (Acidez Volátil)";
$descr_acetified = "Acetato de etila (solvente, esmalte de unha) ou ácido acético (vinagre, áspero no fundo da garganta).";
$label_acidic = "Ácido";
$descr_acidic = "Sabor azedo. Normalmente de um dos vários ácidos: málico, láctico ou cítrico. Deve estar em equilíbrio.";
$descr_acidic_mead = "Sabor / aroma limpo e ácido de baixo pH. Normalmente de um dos vários ácidos: málico, lático, glucônico ou cítrico.";
$label_bitter = "Amargo";
$descr_bitter = "Um sabor forte que é desagradável em níveis mais altos.";
$label_farmyard = "Fazenda";
$descr_farmyard = "Semelhante a estrume (vaca ou porco) ou curral (estábulo de cavalo em um dia quente).";
$label_fruity = "Frutado";
$descr_fruity = "O aroma e o sabor das frutas frescas podem ser apropriados para alguns estilos e não para outros.";
$descr_fruity_mead = "Ésteres de sabor e aroma geralmente derivados de frutas em um melomel. Banana e abacaxi costumam ter sabores estranhos.";
$label_mousy = "Semelhante a um Rato";
$descr_mousy = "Sabor que evoca o cheiro da toca de um roedor.";
$label_oaky = "Carvalho";
$descr_oaky = "Um sabor ou aroma devido a um longo período de tempo em um barril ou em aparas de madeira. &quot;Caráter de barril.&quot;";
$label_oily_ropy = "Oleoso/Viscoso";
$descr_oily_ropy = "Um brilho na aparência visual, como um personagem viscoso desagradável que se transforma em um personagem pegajoso.";
$label_spicy_smoky = "Picante/Enfumaçado";
$descr_spicy_smoky = "Spice, cravo, fumê, presunto.";
$label_sulfide = "Sulfureto";
$descr_sulfide = "Ovos podres, por problemas de fermentação.";
$label_sulfite = "Sulfito";
$descr_sulfite = "Fósforos ardentes, por sulfitação excessiva / recente.";
$label_sweet = "Doce";
$descr_sweet = "Gosto básico de açúcar. Deve estar em equilíbrio, se presente.";
$label_thin = "Fino";
$descr_thin = "Aguado. Sem corpo.";
$label_acetic = "Acético";
$descr_acetic = "Vinagre, ácido acético, forte.";
$label_chemical = "Químico";
$descr_chemical = "Vitamina, nutriente ou sabor químico.";
$label_cloying = "Enjoativo";
$descr_cloying = "Xarope, excessivamente doce, desequilibrado por ácido / tanino.";
$label_floral = "Floral";
$descr_floral = "O aroma de flores em flor ou perfume.";
$label_moldy = "Mofado";
$descr_moldy = "Aromas / sabores rançosos, mofados, mofados ou com rolha.";
$label_tannic = "Tânico";
$descr_tannic = "Sensação na boca seca, adstringente e adstringente, semelhante ao sabor amargo. Gosto de chá forte sem açúcar ou mastigação de casca de uva.";
$label_waxy = "Ceroso";
$descr_waxy = "Como cera, sebo, gorduroso.";
$label_medicinal = "Medicinal";
$label_spicy = "Picante";
$label_vinegary = "Vinagre";
$label_plastic = "Plástico";
$label_smoky = "Enfumaçado";

$label_inappropriate = "Inapropriado";
$label_possible_points = "Pontos Possíveis";
$label_malt = "Malte";
$label_ferm_char = "Caráter de Fermentação";
$label_body = "Corpo";
$label_creaminess = "Cremosidade";
$label_astringency = "Adstringência";
$label_warmth = "Calor";
$label_appearance = "Aparência";
$label_flavor = "Sabor";
$label_mouthfeel = "Sensação na Boca";
$label_overall_impression = "Impressão Geral";
$label_balance = "Saldo";
$label_finish_aftertaste = "Gosto Residual";
$label_hoppy = "Hoppy";
$label_malty = "Maltado";
$label_comments = "Comentários";
$label_flaws = "Falhas de Estilo";
$label_flaw = "Falha";
$label_flawless = "Sem Falhas";
$label_significant_flaws = "Falhas Significativas";
$label_classic_example = "Exemplo Clássico";
$label_not_style = "Não Estilizar";
$label_tech_merit = "Mérito Técnico";
$label_style_accuracy = "Precisão Estilística";
$label_intangibles = "Intangivel";
$label_wonderful = "Maravilhosa";
$label_lifeless = "Sem Vida";
$label_feedback = "Comentários";
$label_honey = "Mel";
$label_alcohol = "Álcool";
$label_complexity = "Complexidade";
$label_viscous = "Viscoso";
$label_legs = "Pernas"; // Used to describe liquid clinging to glass
$label_clarity = "Clareza";
$label_brilliant = "Brilhante";
$label_hazy = "Nebuloso";
$label_opaque = "Opaco";
$label_fruit = "Fruta";
$label_acidity = "Acidez";
$label_tannin = "Tanino";

$label_white = "Branco";
$label_straw = "Cor de Palha";
$label_yellow = "Amarelo";
$label_gold = "Ouro";
$label_copper = "Cobre";
$label_quick = "Rápido";
$label_long_lasting = "De Longa Duração";
$label_ivory = "Cor de Marfim";
$label_beige = "Bege";
$label_tan = "Cor Morena";
$label_lacing = "Laço";
$label_particulate = "Particulado";
$label_black = "Preto";
$label_large = "Ampla";
$label_small = "Pequeno";
$label_size = "Tamanho";
$label_retention = "Retenção";
$label_head = "Cabeça"; // beer foam / head
$label_head_size = "Tamanho da Cabeça";
$label_head_retention = "Retenção de Cabeça";
$label_head_color = "Cor da Cabeça";
$label_brettanomyces = "Brettanomyces";
$label_cardboard = "Cartão";
$label_cloudy = "Nublado";
$label_sherry = "Xerez";
$label_harsh = "Áspero";
$label_harshness = "Aspereza";
$label_full = "Cheio";
$label_suggested = "Sugerido";
$label_lactic = "Láctico";
$label_smoke = "Fumaça";
$label_spice = "Especiaria";
$label_vinous = "Vinoso";
$label_wood = "Madeira";
$label_cream = "Creme";
$label_flat = "Plano";

$label_descriptor_defs = "Definições do descritor";
$label_outstanding = "Excepcional";
$descr_outstanding = "Exemplo de estilo de classe mundial.";
$label_excellent = "Excelente";
$descr_excellent = "Exemplifica bem o estilo, requer um pequeno ajuste fino.";
$label_very_good = "Muito Bom";
$descr_very_good = "Geralmente dentro dos parâmetros de estilo, algumas pequenas falhas.";
$label_good = "Bom";
$descr_good = "Perde a marca no estilo e / ou pequenas falhas.";
$label_fair = "Decente";
$descr_fair = "Sabores / aromas estranhos ou deficiências de estilo importantes. Desagradável.";
$label_problematic = "Problemático";
$descr_problematic = "Os principais sabores e aromas estranhos dominam. Difícil de beber.";

/**
 * ------------------------------------------------------------------------
 * Version 2.3.0 Additions
 * Via Google Translate English to Portuguese - and for that, I'm sorry. 
 * Again. :)
 * ------------------------------------------------------------------------
 */

$winners_text_006 = "Observação: os resultados desta tabela podem estar incompletos devido à entrada insuficiente ou informações de estilo.";

$label_elapsed_time = "Tempo decorrido";
$label_judge_score = "Pontuações do juiz";
$label_judge_consensus_scores = "Pontuações do consenso do juiz";
$label_your_consensus_score = "Sua pontuação de consenso";
$label_score_range_status = "Status da faixa de pontuação";
$label_consensus_caution = "Cuidado de consenso";
$label_consensus_match = "Correspondência de consenso";
$label_score_range_caution = "Cuidado com o intervalo de pontuação dos juízes";
$label_score_range_ok = "Faixa de pontuação dos juízes OK";
$label_auto_log_out = "Logout automático em";
$label_place_previously_selected = "Local Selecionado Anteriormente";
$label_entry_without_eval = "Inscrição sem avaliação";
$label_entries_with_eval = "Inscrições com avaliação";
$label_entries_without_eval = "Inscrições sem avaliação";
$label_judging_close = "Julgando de Fechamento";
$label_session_expire = "A sessão está prestes a expirar";
$label_refresh = "Atualizar esta página";
$label_stay_here = "Fique aqui";
$label_bottle_inspection = "Inspeção de Garrafa";
$label_bottle_inspection_comments = "Comentários de inspeção de garrafa";
$label_consensus_no_match = "As pontuações de consenso não correspondem";
$label_score_below_courtesy = "A pontuação inserida está abaixo do limite de pontuação de cortesia";
$label_score_greater_50 = "A pontuação inserida é maior que 50";
$label_score_out_range = "A pontuação está fora do intervalo";
$label_score_range = "Faixa de pontuação";
$label_ok = "Certo";
$label_esters = "Ésteres";
$label_phenols = "Fenóis";
$label_descriptors = "Descritores";
$label_grainy = "Granulado";
$label_caramel = "Caramelo";
$label_bready = "Bready";
$label_rich = "Rica";
$label_dark_fruit = "Fruta Escura";
$label_toasty = "Tostado";
$label_roasty = "Torrado";
$label_burnt = "Queimado";
$label_citrusy = "Cítrico";
$label_earthy = "Terroso";
$label_herbal = "Erval";
$label_piney = "Piney";
$label_woody = "Woody";
$label_apple_pear = "Maçã/Pera";
$label_banana = "Banana";
$label_berry = "Baga";
$label_citrus = "Citrino";
$label_dried_fruit = "Fruta Seca";
$label_grape = "Uva";
$label_stone_fruit = "Fruta de Caroço";
$label_even = "Uniforme";
$label_gushed = "Jorrado";
$label_hot = "Quente";
$label_slick = "Liso";
$label_finish = "Terminar";
$label_biting = "Mordendo";
$label_drinkability = "Bebibilidade";
$label_bouquet = "Ramalhete";
$label_of = "Do";
$label_fault = "Culpa";
$label_weeks = "Semanas";
$label_days = "Dias";
$label_scoresheet = "Súmula";
$label_beer_scoresheet = "Folha de Pontuação da Cerveja";
$label_cider_scoresheet = "Folha de Pontuação da Cidra";
$label_mead_scoresheet = "Folha de Pontuação da Hidromel";
$label_consensus_status = "Status de Consenso";

$evaluation_info_044 = "Sua pontuação de consenso não corresponde às inseridas por outros juízes.";
$evaluation_info_045 = "A pontuação de consenso inserida corresponde àquela inserida por juízes anteriores.";
$evaluation_info_046 = "A diferença de pontuação é maior do que";
$evaluation_info_047 = "A diferença de pontuação está dentro da faixa aceitável.";
$evaluation_info_048 = "O lugar que você especificou já foi inserido para a mesa. Escolha outro local ou nenhum local (nenhum).";
$evaluation_info_049 = "Essas entradas não têm pelo menos uma avaliação";
$evaluation_info_050 = "Forneça a posição ordinal da entrada no vôo.";
$evaluation_info_051 = "Forneça o número total de entradas no voo.";
$evaluation_info_052 = "Tamanho apropriado, tampa, nível de preenchimento, remoção de etiqueta, etc.";
$evaluation_info_053 = "A pontuação consensual é a pontuação final acordada por todos os juízes que avaliam a inscrição. Se a pontuação de consenso for desconhecida neste momento, insira sua própria pontuação. Se a pontuação de consenso inserida aqui diferir daquela inserida por outros juízes, você será notificado.";
$evaluation_info_054 = "Esta entrada avançou para uma rodada mini-BOS.";
$evaluation_info_055 = "A pontuação de consenso que você inseriu não corresponde às inseridas por juízes anteriores para esta entrada. Consulte outros juízes que estão avaliando esta inscrição e revise sua pontuação de consenso conforme necessário.";
$evaluation_info_056 = "A pontuação que você inseriu está abaixo de 13, <a href=\"https://www.bjcp.org/cep/GreatBeerJudging.pdf\" target=\"_blank\">um limite de cortesia comumente conhecido para juízes BJCP</a>. Consulte outros juízes e revise sua pontuação conforme necessário.";
$evaluation_info_057 = "As pontuações não devem ser inferiores a 5 nem superiores a 50.";
$evaluation_info_058 = "A pontuação que você inseriu é superior a 50, a pontuação máxima para qualquer entrada. Reveja e reveja a sua pontuação de consenso.";
$evaluation_info_059 = "A pontuação que você forneceu para esta inscrição está fora da faixa de diferença de pontuação entre os juízes.";
$evaluation_info_060 = "caracteres no máximo";
$evaluation_info_061 = "Por favor, forneça alguns comentários breves.";
$evaluation_info_062 = "Escolha um descritor.";
$evaluation_info_063 = "Eu terminaria esta amostra.";
$evaluation_info_064 = "Eu beberia meio litro desta cerveja.";
$evaluation_info_065 = "Eu pagaria por esta cerveja.";
$evaluation_info_066 = "Eu recomendaria esta cerveja.";
$evaluation_info_067 = "Forneça uma classificação.";
$evaluation_info_068 = "Forneça a pontuação de consenso - mínimo de 5, máximo de 50.";
$evaluation_info_069 = "Pelo menos dois juízes do voo em que sua inscrição foi inscrita chegaram a um consenso sobre a pontuação final atribuída. Não é necessariamente uma média das pontuações individuais.";
$evaluation_info_070 = "Com base na planilha de pontuação BJCP para";
$evaluation_info_071 = "15+ minutos se passaram.";
$evaluation_info_072 = "Por padrão, o Logout automático é estendido para 30 minutos para avaliações de entrada.";

$alert_text_090 = "Sua sessão irá expirar em dois minutos. Você pode permanecer na página atual para terminar seu trabalho antes que o tempo expire, atualizar esta página para continuar sua sessão atual (os dados do formulário podem ser perdidos) ou fazer logout.";
$alert_text_091 = "Sua sessão irá expirar em 30 segundos. Você pode atualizar para continuar sua sessão atual ou fazer logout.";
$alert_text_092 = "Deve ser definida pelo menos uma sessão de julgamento para adicionar uma mesa.";

$brewer_entries_text_026 = "As planilhas de pontuação dos juízes para esta inscrição estão em vários formatos. Cada formato contém uma ou mais avaliações válidas desta entrada.";

// Update QR text
$qr_text_008 = "Para fazer o check-in das entradas por meio do código QR, forneça a senha correta. Você só precisará fornecer a senha uma vez por sessão - certifique-se de manter o navegador ou o aplicativo de leitura de código QR aberto.";
$qr_text_015 = "Digitalize o próximo código QR. Para sistemas operacionais mais recentes, acesse o aplicativo da câmera do seu dispositivo móvel. Para sistemas operacionais mais antigos, inicie / volte para o aplicativo de digitalização.";
$qr_text_017 = "A leitura de código QR está disponível nativamente na maioria dos sistemas operacionais móveis modernos. Basta apontar sua câmera para o código QR no rótulo de uma garrafa e seguir as instruções. Para sistemas operacionais móveis mais antigos, um aplicativo de leitura de código QR é necessário para utilizar este recurso.";
$qr_text_018 = "Digitalize um código QR localizado no rótulo de um frasco, digite a senha necessária e verifique a entrada.";

/**
 * ------------------------------------------------------------------------
 * Version 2.4.0 Additions
 * Via Google Translate English to Portuguese - and for that, I'm sorry. 
 * Again. :)
 * ------------------------------------------------------------------------
 */

$label_select_state = "Selecione ou pesquise o seu estado";
$label_select_below = "Selecione abaixo";
$output_text_033 = "Ao enviar seu relatório ao BJCP, é possível que nem todos os integrantes da lista de funcionários recebam pontos. É sugerido que você aloque pontos para aqueles com IDs BJCP primeiro.";
$styles_entry_text_PRX3 = "O participante deve especificar o varietal de uva ou mosto de uva utilizado.";
$styles_entry_text_C1A = "Os participantes DEVEM especificar o nível de carbonatação (3 níveis). Os participantes DEVEM especificar a doçura (5 categorias). Se o OG estiver substancialmente acima da faixa típica, o entrante deve explicar, por exemplo, a variedade particular de maçã que dá suco de alta gravidade.";
$styles_entry_text_C1B = "Os participantes DEVEM indicar o nível de carbonatação (3 níveis). Os participantes DEVEM indicar a doçura (seca a média doçura, 4 níveis). Os participantes DEVEM indicar a variedade de maçã para cidra varietal única; se indicado, é esperado o caráter varietal.";
$styles_entry_text_C1C = "Os participantes DEVEM especificar o nível de carbonatação (3 níveis). Os participantes DEVEM especificar o nível de doçura (somente de média a doce, 3 níveis). Os participantes PODEM especificar a variedade de maçã para uma única cidra varietal; se especificado, será esperado um caráter varietal.";
$winners_text_007 = "Não há entradas vencedoras nesta tabela.";

/**
 * ------------------------------------------------------------------------
 * Version 2.4.0 Additions
 * Via DeepL Translator English to Portuguese - and for that, I'm sorry. 
 * Again. :)
 * ------------------------------------------------------------------------
 */

$label_entries_to_judge = "Entradas para Julgar";
$evaluation_info_073 = "Se você tiver alterado ou adicionado qualquer item ou comentário nesta folha de pontuação, seus dados poderão ser perdidos se você navegar para longe desta página.";
$evaluation_info_074 = "Se você TENHA feito alterações, feche este diálogo, role até o final da folha de pontuação e selecione Submeter Avaliação.";
$evaluation_info_075 = "Se você NÃO tiver feito nenhuma mudança, selecione o botão azul do Painel de Julgamento abaixo.";
$evaluation_info_076 = "Comente sobre malte, lúpulo, ésteres e outros aromáticos.";
$evaluation_info_077 = "Comente sobre cor, clareza e cabeça (retenção, cor e textura).";
$evaluation_info_078 = "Comente sobre malte, lúpulo, características de fermentação, equilíbrio, acabamento/sabor, e outras características de sabor.";
$evaluation_info_079 = "Comentário sobre o corpo, carbonação, calor, cremosidade, adstringência e outras sensações palatinas.";
$evaluation_info_080 = "Comente sobre o prazer geral de beber associado à entrada, dê sugestões para melhorias.";

if ($_SESSION['prefsStyleSet'] == "BJCP2021") {
    $styles_entry_text_21B = "O participante DEVE especificar uma força (sessão, padrão, duplo); se nenhuma força for especificada, o padrão será assumido. O participante DEVE especificar o tipo específico de Especialidade IPA da lista de Tipos Definidos Atualmente identificados nas Diretrizes de Estilo, ou conforme emendado pelos Estilos Provisórios no site do BJCP; OU o participante DEVE descrever o tipo de Especialidade IPA e suas principais características em forma de comentários para que os juízes saibam o que esperar. Os participantes PODEM especificar as variedades específicas de lúpulo utilizadas, se os participantes acharem que os juízes podem não reconhecer as características varietais dos lúpulos mais recentes. Os participantes PODEM especificar uma combinação de tipos IPA definidos (por exemplo, IPA Centeio Negro) sem fornecer descrições adicionais.";
    $styles_entry_text_24C = "O participante DEVE especificar a Bière de Garde loira, âmbar ou marrom.";
    $styles_entry_text_25B = "O participante DEVE especificar a força (tabela, padrão, super) e a cor (pálido, escuro). O participante PODE identificar os grãos de caráter utilizados.";
    $styles_entry_text_27A = "Categoria para outras cervejas históricas que NÃO tenham sido definidas pelo BJCP. O participante DEVE fornecer uma descrição para os juízes do estilo histórico que NÃO é um dos exemplos de estilo histórico definido atualmente fornecidos pelo BJCP. Exemplos atualmente definidos: Kellerbier, Kentucky Common, Lichtenhainer, London Brown Ale, Piwo Grodziskie, Pre-Prohibition Lager, Pre-Prohibition Porter, Roggenbier, Sahti. Se uma cerveja é entrada apenas com um nome de estilo e sem descrição, é muito improvável que os juízes entendam como julgá-la.";
    $styles_entry_text_28A = "O participante DEVE especificar ou um Estilo Base, ou fornecer uma descrição dos ingredientes, especificações, ou caráter desejado. O participante PODE especificar as linhagens de Brett utilizadas.";
    $styles_entry_text_28B = "O participante DEVE especificar uma descrição da cerveja, identificando a levedura ou as bactérias usadas e um Estilo Base, ou os ingredientes, especificações, ou o caráter alvo da cerveja.";
    $styles_entry_text_28C = "O participante DEVE especificar qualquer ingrediente de tipo especial (por exemplo, fruta, especiarias, ervas ou madeira) utilizado. O participante DEVE especificar ou uma descrição da cerveja, identificando a levedura ou as bactérias usadas e um Estilo Base, ou os ingredientes, especificações, ou o caráter alvo da cerveja. Uma descrição geral da natureza especial da cerveja pode cobrir todos os itens necessários.";
    $styles_entry_text_29A = "O participante DEVE especificar o(s) tipo(s) de fruta(s) utilizado(s). O participante DEVE especificar uma descrição da cerveja, identificando um Estilo Base ou os ingredientes, especificações ou o caráter alvo da cerveja. Uma descrição geral da natureza especial da cerveja pode cobrir todos os itens necessários.  Cervejas de fruta baseadas no estilo clássico devem ser inseridas neste estilo, exceto a Lambic.";
    $styles_entry_text_29B = "O participante deve especificar o tipo de fruta e o tipo de SHV utilizado; os ingredientes individuais de SHV não precisam ser especificados se for utilizada uma mistura bem conhecida de especiarias (por exemplo, especiarias de torta de maçã). O participante deve especificar uma descrição da cerveja, seja um Estilo Base ou os ingredientes, especificações ou o caráter alvo da cerveja. Uma descrição geral da natureza especial da cerveja pode cobrir todos os itens necessários.";
    $styles_entry_text_29C = "O entrante DEVE especificar o tipo de fruta utilizada. O participante DEVE especificar o tipo de ingrediente adicional (conforme a introdução) ou processo especial empregado. O participante DEVE especificar uma descrição da cerveja, identificando um Estilo Base ou os ingredientes, especificações ou caráter alvo da cerveja. Uma descrição geral da natureza especial da cerveja pode cobrir todos os itens necessários.";
    $styles_entry_text_29D = "O entrante DEVE especificar o tipo de uva utilizada. O participante PODE fornecer informações adicionais sobre o estilo de base ou ingredientes característicos.";
    $styles_entry_text_30A = "O participante DEVE especificar o tipo de especiarias, ervas ou vegetais utilizados, mas os ingredientes individuais não precisam ser especificados se for utilizada uma mistura de especiarias bem conhecida (por exemplo, especiarias para torta de maçã, caril em pó, chili em pó). O participante DEVE especificar uma descrição da cerveja, identificando um Estilo Base ou os ingredientes, especificações ou o caráter alvo da cerveja. Uma descrição geral da natureza especial da cerveja pode cobrir todos os itens necessários.";
    $styles_entry_text_30B = "O participante DEVE especificar o tipo de especiarias, ervas ou vegetais utilizados; os ingredientes individuais não precisam ser especificados se for utilizada uma mistura bem conhecida de especiarias (por exemplo, tempero para torta de abóbora). O participante DEVE especificar uma descrição da cerveja, identificando um Estilo Base ou os ingredientes, especificações ou o caráter alvo da cerveja. Uma descrição geral da natureza especial da cerveja pode cobrir todos os itens necessários.";
    $styles_entry_text_30C = "O participante DEVE especificar o tipo de especiarias, açúcares, frutas ou fermentáveis adicionais utilizados; os ingredientes individuais não precisam ser especificados se for utilizada uma mistura bem conhecida de especiarias (por exemplo, especiarias mulling). O participante DEVE especificar uma descrição da cerveja, identificando um Estilo Base ou os ingredientes, especificações ou o caráter alvo da cerveja. Uma descrição geral da natureza especial da cerveja pode cobrir todos os itens necessários.";
    $styles_entry_text_30D = "O participante DEVE especificar o tipo de SHV utilizado, mas os ingredientes individuais não precisam ser especificados se for utilizada uma mistura de especiarias bem conhecida (por exemplo, especiarias para torta de maçã, caril em pó, pimenta em pó). O participante DEVE especificar o tipo de ingrediente adicional (de acordo com a introdução) ou o processo especial empregado. O participante DEVE especificar uma descrição da cerveja, identificando um Estilo Base ou os ingredientes, especificações ou o caráter alvo da cerveja. Uma descrição geral da natureza especial da cerveja pode cobrir todos os itens necessários.";
    $styles_entry_text_31A = "O participante deve especificar o tipo de grão alternativo utilizado. O participante deve especificar uma descrição da cerveja, identificando o Estilo Base ou os ingredientes, especificações ou o caráter alvo da cerveja. Uma descrição geral da natureza especial da cerveja pode cobrir todos os itens necessários.";
    $styles_entry_text_31B = "O entrante DEVE especificar o tipo de açúcar utilizado. O participante DEVE especificar uma descrição da cerveja, identificando um Estilo Base ou os ingredientes, especificações ou o caráter alvo da cerveja. Uma descrição geral da natureza especial da cerveja pode cobrir todos os itens necessários.";
    $styles_entry_text_32A = "O participante DEVE especificar um Estilo Base. O participante DEVE especificar o tipo de madeira ou fumaça se um caractere varietal de fumaça for perceptível.";
    $styles_entry_text_32B = "O entrante DEVE especificar o tipo de madeira ou fumaça se um caráter de fumaça varietal for perceptível. O entrante DEVE especificar os ingredientes ou processos adicionais que fazem desta uma cerveja defumada especial. O participante DEVE especificar uma descrição da cerveja, identificando um estilo base ou os ingredientes, especificações, ou o caráter alvo da cerveja. Uma descrição geral da natureza especial da cerveja pode cobrir todos os itens necessários.";
    $styles_entry_text_33A = "O entrante DEVE especificar o tipo de madeira utilizada e o nível de torrada ou char (se usado). Se for usada uma madeira varietal incomum, o participante DEVE fornecer uma breve descrição dos aspectos sensoriais que a madeira acrescenta à cerveja. O entrante DEVE especificar uma descrição da cerveja, identificando o Estilo Base ou os ingredientes, especificações ou o caráter alvo da cerveja. Uma descrição geral da natureza especial da cerveja pode cobrir todos os itens necessários.";
    $styles_entry_text_33B = "O participante DEVE especificar o caráter alcoólico adicional, com informações sobre o barril se relevante para o perfil de sabor acabado. Se uma madeira ou ingrediente incomum tiver sido utilizado, o participante DEVE fornecer uma breve descrição dos aspectos sensoriais que os ingredientes acrescentam à cerveja. O entrante DEVE especificar uma descrição da cerveja, identificando o Estilo Base ou os ingredientes, especificações ou o caráter alvo da cerveja. Uma descrição geral da natureza especial da cerveja pode cobrir todos os itens necessários.";
    $styles_entry_text_34A = "O participante DEVE especificar o nome da cerveja comercial, especificações (estatísticas vitais) para a cerveja e uma breve descrição sensorial ou uma lista de ingredientes usados na fabricação da cerveja. Sem esta informação, os juízes que não estiverem familiarizados com a cerveja não terão base para comparação.";
    $styles_entry_text_34B = "O participante DEVE especificar o Estilo ou Estilos Base utilizados, e quaisquer ingredientes especiais, processos ou variações envolvidas. O participante PODE fornecer uma descrição adicional do perfil sensorial da cerveja ou as estatísticas vitais da cerveja resultante.";
    $styles_entry_text_PRX3 = "O entrante DEVE especificar o tipo de uva utilizada. O participante PODE fornecer informações adicionais sobre o estilo de base ou ingredientes característicos.";
}

/**
 * ------------------------------------------------------------------------
 * Version 2.5.0 Additions
 * Via DeepL Translator English to Portuguese - and for that, I'm sorry. 
 * Again. :)
 * ------------------------------------------------------------------------
 */

$register_text_047 = "Sua pergunta e/ou resposta de segurança mudou.";
$register_text_048 = "Se você não iniciou esta mudança, sua conta pode estar comprometida. Você deve entrar imediatamente em sua conta e alterar sua senha, além de atualizar sua pergunta e resposta de segurança.";
$register_text_049 = "Se você não conseguir entrar em sua conta, você deve entrar imediatamente em contato com um administrador do site para atualizar sua senha e outras informações vitais da conta.";
$register_text_050 = "A resposta de sua pergunta de segurança é criptografada e não pode ser lida pelos administradores do site. Ela deve ser inserida se você optar por mudar sua pergunta e/ou resposta de segurança.";
$register_text_051 = "Informações de Conta Atualizadas";
$register_text_052 = "Uma resposta de Sim ou Não é necessária para cada local abaixo.";
$brewer_text_044 = "Você deseja mudar sua pergunta e/ou resposta de segurança?";
$brewer_text_045 = "Não se registraram resultados.";
$brewer_text_046 = "Para a entrada de nomes de clubes de forma livre, alguns símbolos não são permitidos, incluindo ampersand (&amp;), marcas de cotações simples (&#39;), marcas de cotações duplas (&quot;), e porcentagem (&#37;).";
$brewer_text_047 = "Se você não estiver disponível para qualquer uma das sessões listadas abaixo, mas ainda pode servir como funcionário em outra função, selecione Sim.";
$brewer_text_048 = "Enviarei por correios";
$brewer_text_049 = "Selecione \"Não Aplicável\" se você não planeja apresentar nenhuma participação na competição.";
$brewer_text_050 = "Selecione \"Enviarei por correios\" se você planeja encaixotar e enviar suas entradas para o local de embarque fornecido.";
$label_change_security = "Mudar pergunta/resposta de segurança?";
$label_semi_dry = "Semi-Seco";
$label_semi_sweet = "Semi-Doce";
$label_shipping_location = "Localização da Expedição";
$volunteers_text_010 = "O pessoal pode indicar sua disponibilidade para as seguintes sessões sem juízos de valor:";

$evaluation_info_081 = "Comentário sobre a expressão do mel, álcool, ésteres, complexidade e outros aromáticos.";
$evaluation_info_082 = "Comentário sobre cor, clareza, pernas e carbonatação.";
$evaluation_info_083 = "Comentário sobre mel, doçura, acidez, tanino, álcool, equilíbrio, corpo, carbonatação, gosto residual e quaisquer ingredientes especiais ou sabores específicos de estilo.";
$evaluation_info_084 = "Comente sobre o prazer geral de beber associado à entrada, dê sugestões para melhorias.";
$evaluation_info_085 = "Cor (2), clareza (2), nível de carbonatação (2).";
$evaluation_info_086 = "Expressão de outros ingredientes, conforme apropriado.";
$evaluation_info_087 = "Equilíbrio de acidez, doçura, graduação alcoólica, corpo, carbonatação (se apropriado) (14), Outros ingredientes, se apropriado (5), Gosto residual (5).";
$evaluation_info_088 = "Comente sobre o prazer geral de beber associado à entrada, dê sugestões para melhorias.";

$evaluation_info_089 = "Contagem mínima de palavras atingida ou excedida.";
$evaluation_info_090 = "Obrigado por fornecer a avaliação mais completa possível. Palavras totais: ";
$evaluation_info_091 = "Palavras mínimas necessárias para seus comentários: ";
$evaluation_info_092 = "A contagem de palavras até o momento: ";
$evaluation_info_093 = "O requisito mínimo de palavras não foi alcançado no campo de Feedback de Impressão Geral acima.";
$evaluation_info_094 = "O requisito mínimo de palavras não foi alcançado em um ou mais campos de feedback / comentário acima.";

/**
 * ------------------------------------------------------------------------
 * Version 2.6.0 Additions
 * ------------------------------------------------------------------------
 */

$label_regional_variation = "Variação Regional";
$label_characteristics = "Características";
$label_intensity = "Intensidade";
$label_quality = "Qualidade";
$label_palate = "Palato";
$label_medium = "Médio";
$label_medium_dry = "Meio Seco";
$label_medium_sweet = "Doce Médio";
$label_your_score = "Sua Pontuação";
$label_summary_overall_impression = "Resumo da Avaliação e Impressão Geral";
$label_medal_count = "Contagem de Grupos de Medalhas";
$label_best_brewer_place = "Melhor Local para Cervejarias";
$label_industry_affiliations = "Participação em Organizações do Setor";
$label_deep_gold = "Ouro Profundo";
$label_chestnut = "Castanha";
$label_pink = "Rosa";
$label_red = "Vermelho";
$label_purple = "Roxo";
$label_garnet = "Granada";
$label_clear = "Transparente";
$label_final_judging_date = "Data do Julgamento Final";
$label_entries_judged = "Inscrições julgadas";
$label_results_export = "Exportação Resultados";
$label_results_export_personal = "Exportar Resultados Pessoais";

$brew_text_041 = "Opcional - especificar uma variação regional (por exemplo, Lager mexicana, Lager holandesa, Rice Lager japonesa, etc.).";
$evaluation_info_095 = "Próxima sessão de julgamento designada aberta:";
$evaluation_info_096 = "Para ajudar na preparação, mesas/voos designados e entradas associadas estão disponíveis dez minutos antes do início de uma sessão.";
$evaluation_info_097 = "Sua próxima sessão de julgamento já está disponível.";
$evaluation_info_098 = "Refresque-se para ver.";
$evaluation_info_099 = "Sessões de julgamento passadas ou atuais:";
$evaluation_info_100 = "Próximas sessões de julgamento:";
$evaluation_info_101 = "Forneça outro descritor de cor.";
$evaluation_info_102 = "Digite sua pontuação total - máximo de 50. Use o guia de pontuação abaixo, se necessário.";
$evaluation_info_103 = "Forneça sua pontuação - mínimo de 5, máximo de 50.";

$brewer_text_051 = "Selecione as organizações do setor às quais você é afiliado como funcionário, voluntário, etc. Isso serve para garantir que não haja conflitos de interesse ao designar juízes e comissários de bordo para avaliar as inscrições.";
$brewer_text_052 = "<strong>Se alguma organização do setor <u>não</u> estiver listada no menu suspenso acima, insira-a aqui.</strong> Separe o nome de cada organização por vírgula (,) ou ponto e vírgula (;). Alguns símbolos não são permitidos, incluindo aspas duplas (&quot;) e porcentagem (&#37;).";


/**
 * ----------------------------------------------------------------------------------
 * END TRANSLATIONS
 * ----------------------------------------------------------------------------------
 */

/**
 * ----------------------------------------------------------------------------------
 * Various conditionals
 * No translations below this line
 * ----------------------------------------------------------------------------------
 */

if (strpos($section, "step") === FALSE) $alert_text_032 = $alert_text_032; else $alert_text_032 = "";
if (strpos($section, "step") === FALSE) $alert_text_033 = $alert_text_033; else $alert_text_033 = "";
if (strpos($section, "step") === FALSE) $alert_text_036 = $alert_text_036; else $alert_text_036 = "";
if (strpos($section, "step") === FALSE) $alert_text_039 = $alert_text_039; else $alert_text_039 = "";
if ((strpos($section, "step") === FALSE) && ((isset($_SESSION['prefsProEdition'])) && ($_SESSION['prefsProEdition'] == 0))) $alert_text_043 = $alert_text_043; else $alert_text_043 = "";
if ((strpos($section, "step") === FALSE) && ((isset($_SESSION['prefsProEdition'])) && ($_SESSION['prefsProEdition'] == 0))) $alert_text_047 = $alert_text_047; else $alert_text_047 = "";
if (strpos($section, "step") === FALSE) $alert_text_050 = $alert_text_050; else $alert_text_050 = "";
if (strpos($section, "step") === FALSE) $alert_text_053 = $alert_text_053; else $alert_text_053 = "";
if ((strpos($section, "step") === FALSE) && ((isset($_SESSION['prefsProEdition'])) && ($_SESSION['prefsProEdition'] == 0))) $alert_text_060 = $alert_text_060; else $alert_text_060 = "";
if (strpos($section, "step") === FALSE) $alert_text_068 = $alert_text_068; else $alert_text_068 = "";
if (strpos($section, "step") === FALSE) $alert_text_070 = $alert_text_070; else $alert_text_070 = "";
if (strpos($section, "step") === FALSE) $label_character_limit = $label_character_limit; else $label_character_limit = "";
if (strpos($section, "step") === FALSE) $header_text_031 = $header_text_031; else $header_text_031 = "";
if (strpos($section, "step") === FALSE) $beerxml_text_007 = $beerxml_text_007; else $beerxml_text_007 = "";

/**
 * ----------------------------------------------------------------------------------
 * Admin Pages - Admin pages will be included in a future release
 * ----------------------------------------------------------------------------------
 */
// if ($section == "admin") include (LANG.'en_admin.lang.php');

?>