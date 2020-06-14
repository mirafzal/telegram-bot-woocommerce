<?php
$time_start = microtime(true);

require_once 'Telegram.php';
require_once 'User.php';
require_once 'Pages.php';
require_once 'Texts.php';
require_once 'Categories.php';

$bot_token =
    '1248190728:AAHdOmSP4kA7yGMWwM0zNkLkI-O1odG440o';

$telegram = new Telegram($bot_token);

$rootPath = "https://roboss.uz/ymobot/";

// $telegram->setWebhook("{$rootPath}bot.php");

echo "Vse norm";

$ADMIN_CHAT_ID = 635793263;

$ADMINS_CHAT_IDS = [
    635793263,
];

$callback_query = $telegram->Callback_Query();

$data = $telegram->getData();
$message = $data['message'];
$text = $message['text'];
$chatID = $message['chat']['id'];

if ($chatID == null) $chatID = $ADMIN_CHAT_ID;

$user = new User($chatID);
$texts = new Texts($user->getLanguage());

//$telegram->sendChatAction(['chat_id' => $chatID, 'action' => 'typing']);

if ($text == "/start") {
    showStartPage();
} else {
    switch ($user->getPage()) {
        case Pages::START:
            switch ($text) {
                case "🇺🇿 O'zbekcha":
                    $user->setLanguage('uz');
                    init();
                    showMainPage();
                    break;
                case "🇷🇺 Русский":
                    $user->setLanguage('ru');
                    init();
                    showMainPage();
                    break;
                default:
                    showStartPage();
                    break;
            }
            break;
        case Pages::PAGE_MAIN:
            switch ($text) {
                case $texts->getText('page_main_btn_1'): // produktsiya
                    showProductsPage();
                    break;
                case $texts->getText('page_main_btn_2'): // magazini
                    showShopsPage();
                    break;
                case $texts->getText('page_main_btn_3'): // napisat' nam
                    showWriteUsPage();
                    break;
                case $texts->getText('page_main_btn_4'): // korzina
                    showShoppingCartPage();
                    break;
                case $texts->getText('page_main_btn_5'): // oplata i dostavka
                    showPaymentAndDeliveryPage();
                    break;
                case $texts->getText('page_main_btn_6'): // sposobi dostavki
                    showDeliveryTypesPage();
                    break;
                case $texts->getText('page_main_btn_7'): // o nas
                    showAboutPage();
                    break;
                case $texts->getText('page_main_btn_8'): // kontakti
                    showContactsPage();
                    break;
                case $texts->getText('page_main_btn_9'): // izmenit' yazik
                    reverseLanguage();
                    showMainPage();;
                    break;
                case $texts->getText('page_main_admin_btn'): // admin panel
                    if (in_array($chatID, $ADMINS_CHAT_IDS)) {
                        showAdminPage();
                    }
                    break;
                default:
                    showMainPage();
                    break;
            }
            break;
        case Pages::PAGE_CATEGORIES:
            showProductsPage2($text);
            break;
        case Pages::PAGE_CATEGORIES_2:
            showProductsPage2($text);
            break;
    }
}

// pages
function showStartPage()
{
    global $user;
    $user->setPage(Pages::START);
    $buttons = ["🇷🇺 Русский", "🇺🇿 O'zbekcha"];
    $textToSend = "Пожалуйста выберите язык. 👇\n\nIltimos, tilni tanlang. 👇";
    sendTextWithKeyboard($buttons, $textToSend);
}

function showMainPage()
{
    global $user, $texts, $chatID, $ADMINS_CHAT_IDS;
    $user->setPage(Pages::PAGE_MAIN);
    $buttons = $texts->getArrayLike("page_main_btn");
    if (in_array($chatID, $ADMINS_CHAT_IDS)) {
        $buttons[] = $texts->getText("page_main_admin_btn");
    }
    $textToSend = $texts->getText("page_main_text");
    sendTextWithKeyboard($buttons, $textToSend);
}

function showProductsPage()
{
    global $user, $texts, $time_start;
    $user->setPage(Pages::PAGE_CATEGORIES);
    sendMessage('<b>Total Execution Time:</b> ' . (microtime(true) - $time_start) . ' secs');
    $buttons = Categories::getParentCategories();
    sendMessage('<b>Total Execution Time:</b> ' . (microtime(true) - $time_start) . ' secs');
    $textToSend = $texts->getText("page_categories_text");
    sendTextWithKeyboard($buttons, $textToSend, true);
}

function showProductsPage2($text)
{
    global $user, $texts, $time_start;
    sendMessage('<b>Total Execution Time:</b> ' . (microtime(true) - $time_start) . ' secs');
    if ($buttons = Categories::getChildCategories($text)) {
        sendMessage('<b>Total Execution Time:</b> ' . (microtime(true) - $time_start) . ' secs');
        $user->setPage(Pages::PAGE_CATEGORIES_2);
        $textToSend = $texts->getText("page_categories_text");
        sendTextWithKeyboard($buttons, $textToSend, true);
    } else {
        $user->
        sendMessage('<b>Total Execution Time:</b> ' . (microtime(true) - $time_start) . ' secs');
        $user->setPage(Pages::PAGE_PRODUCTS);
        $buttons = Categories::getProducts($text);
        sendMessage('<b>Total Execution Time:</b> ' . (microtime(true) - $time_start) . ' secs');
        $textToSend = $texts->getText("page_products_text");
        sendTextWithKeyboard($buttons, $textToSend, true);
    }
}

function showShopsPage()
{
    sendMessage("В разработке...");
}

function showWriteUsPage()
{
    sendMessage("В разработке...");

}

function showShoppingCartPage()
{
    sendMessage("В разработке...");
}

function showPaymentAndDeliveryPage()
{
    global $texts;
    sendMessage($texts->getText('payment_and_delivery_page_text'));
}

function showDeliveryTypesPage()
{
    global $rootPath, $telegram;
    $telegram->sendPhoto(['chat_id' => $telegram->ChatID(), 'photo' => "{$rootPath}photos/delivery_types_photo.png"]);
}

function showAboutPage()
{
    global $texts;
    sendMessage($texts->getText('page_about_text'));
}

function showContactsPage()
{
    global $texts;
    sendMessage($texts->getText('page_contacts_text'));
}

function showAdminPage()
{
    sendMessage("В разработке...");
}

function sendTextWithKeyboard($buttons, $text, $backBtn = false)
{
    global $telegram, $chatID, $texts;
    $option = [];
    if (count($buttons) % 2 == 0) {
        for ($i = 0; $i < count($buttons); $i += 2) {
            $option[] = array($telegram->buildKeyboardButton($buttons[$i]), $telegram->buildKeyboardButton($buttons[$i + 1]));
        }
    } else {
        for ($i = 0; $i < count($buttons) - 1; $i += 2) {
            $option[] = array($telegram->buildKeyboardButton($buttons[$i]), $telegram->buildKeyboardButton($buttons[$i + 1]));
        }
        $option[] = array($telegram->buildKeyboardButton(end($buttons)));
    }
    if ($backBtn) {
        $option[] = [$telegram->buildKeyboardButton($texts->getText("back_btn"))];
    }
    $keyboard = $telegram->buildKeyBoard($option, $onetime = false, $resize = true);
    $content = array('chat_id' => $chatID, 'reply_markup' => $keyboard, 'text' => $text, 'parse_mode' => "HTML");
    $telegram->sendMessage($content);
}

function init()
{
    global $chatID, $user, $texts;
    $user = new User($chatID);
    $texts = new Texts($user->getLanguage());
}

function sendMessage($text, $parse_mode = "HTML")
{
    global $telegram;
    $telegram->sendMessage([
        'chat_id' => $telegram->ChatID(),
        'text' => $text,
        'parse_mode' => $parse_mode
    ]);
}

function reverseLanguage()
{
    global $user;
    if ($user->getLanguage() == 'uz') {
        $user->setLanguage('ru');
    } else {
        $user->setLanguage('uz');
    }
    init();
}