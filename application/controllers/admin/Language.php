<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Language extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Curd_model');
        LoginAuthValidate();
    }

    public function add()
    {
        $data['title'] = 'Language Add';
        $this->load->view('admin/language', $data);
    }
    public function list()
    {
        $data['languageArray'] = $this->Curd_model->Select('tbl_languages');
        $data['title'] = 'Language List';
        $this->load->view('admin/language-list', $data);
    }

    public function addLanguage()
    {
        $languages_list = array(
            array("language_name" => "Afrikaans", "code" => "af"),
            array("language_name" => "Albanian - shqip", "code" => "sq"),
            array("language_name" => "Amharic - አማርኛ", "code" => "am"),
            array("language_name" => "Arabic - العربية", "code" => "ar"),
            array("language_name" => "Aragonese - aragonés", "code" => "an"),
            array("language_name" => "Armenian - հայերեն", "code" => "hy"),
            array("language_name" => "Asturian - asturianu", "code" => "ast"),
            array("language_name" => "Azerbaijani - azərbaycan dili", "code" => "az"),
            array("language_name" => "Basque - euskara", "code" => "eu"),
            array("language_name" => "Belarusian - беларуская", "code" => "be"),
            array("language_name" => "Bengali - বাংলা", "code" => "bn"),
            array("language_name" => "Bosnian - bosanski", "code" => "bs"),
            array("language_name" => "Breton - brezhoneg", "code" => "br"),
            array("language_name" => "Bulgarian - български", "code" => "bg"),
            array("language_name" => "Catalan - català", "code" => "ca"),
            array("language_name" => "Central Kurdish - کوردی (دەستنوسی عەرەبی)", "code" => "ckb"),
            array("language_name" => "Chinese - 中文", "code" => "zh"),
            array("language_name" => "Chinese (Hong Kong) - 中文（香港）", "code" => "zh-HK"),
            array("language_name" => "Chinese (Simplified) - 中文（简体）", "code" => "zh-CN"),
            array("language_name" => "Chinese (Traditional) - 中文（繁體）", "code" => "zh-TW"),
            array("language_name" => "Corsican", "code" => "co"),
            array("language_name" => "Croatian - hrvatski", "code" => "hr"),
            array("language_name" => "Czech - čeština", "code" => "cs"),
            array("language_name" => "Danish - dansk", "code" => "da"),
            array("language_name" => "Dutch - Nederlands", "code" => "nl"),
            array("language_name" => "English", "code" => "en"),
            array("language_name" => "English (Australia)", "code" => "en-AU"),
            array("language_name" => "English (Canada)", "code" => "en-CA"),
            array("language_name" => "English (India)", "code" => "en-IN"),
            array("language_name" => "English (New Zealand)", "code" => "en-NZ"),
            array("language_name" => "English (South Africa)", "code" => "en-ZA"),
            array("language_name" => "English (United Kingdom)", "code" => "en-GB"),
            array("language_name" => "English (United States)", "code" => "en-US"),
            array("language_name" => "Esperanto - esperanto", "code" => "eo"),
            array("language_name" => "Estonian - eesti", "code" => "et"),
            array("language_name" => "Faroese - føroyskt", "code" => "fo"),
            array("language_name" => "Filipino", "code" => "fil"),
            array("language_name" => "Finnish - suomi", "code" => "fi"),
            array("language_name" => "French - français", "code" => "fr"),
            array("language_name" => "French (Canada) - français (Canada)", "code" => "fr-CA"),
            array("language_name" => "French (France) - français (France)", "code" => "fr-FR"),
            array("language_name" => "French (Switzerland) - français (Suisse)", "code" => "fr-CH"),
            array("language_name" => "Galician - galego", "code" => "gl"),
            array("language_name" => "Georgian - ქართული", "code" => "ka"),
            array("language_name" => "German - Deutsch", "code" => "de"),
            array("language_name" => "German (Austria) - Deutsch (Österreich)", "code" => "de-AT"),
            array("language_name" => "German (Germany) - Deutsch (Deutschland)", "code" => "de-DE"),
            array("language_name" => "German (Liechtenstein) - Deutsch (Liechtenstein)", "code" => "de-LI"),
            array("language_name" => "German (Switzerland) - Deutsch (Schweiz)", "code" => "de-CH"),
            array("language_name" => "Greek - Ελληνικά", "code" => "el"),
            array("language_name" => "Guarani", "code" => "gn"),
            array("language_name" => "Gujarati - ગુજરાતી", "code" => "gu"),
            array("language_name" => "Hausa", "code" => "ha"),
            array("language_name" => "Hawaiian - ʻŌlelo Hawaiʻi", "code" => "haw"),
            array("language_name" => "Hebrew - עברית", "code" => "he"),
            array("language_name" => "Hindi - हिन्दी", "code" => "hi"),
            array("language_name" => "Hungarian - magyar", "code" => "hu"),
            array("language_name" => "Icelandic - íslenska", "code" => "is"),
            array("language_name" => "Indonesian - Indonesia", "code" => "id"),
            array("language_name" => "Interlingua", "code" => "ia"),
            array("language_name" => "Irish - Gaeilge", "code" => "ga"),
            array("language_name" => "Italian - italiano", "code" => "it"),
            array("language_name" => "Italian (Italy) - italiano (Italia)", "code" => "it-IT"),
            array("language_name" => "Italian (Switzerland) - italiano (Svizzera)", "code" => "it-CH"),
            array("language_name" => "Japanese - 日本語", "code" => "ja"),
            array("language_name" => "Kannada - ಕನ್ನಡ", "code" => "kn"),
            array("language_name" => "Kazakh - қазақ тілі", "code" => "kk"),
            array("language_name" => "Khmer - ខ្មែរ", "code" => "km"),
            array("language_name" => "Korean - 한국어", "code" => "ko"),
            array("language_name" => "Kurdish - Kurdî", "code" => "ku"),
            array("language_name" => "Kyrgyz - кыргызча", "code" => "ky"),
            array("language_name" => "Lao - ລາວ", "code" => "lo"),
            array("language_name" => "Latin", "code" => "la"),
            array("language_name" => "Latvian - latviešu", "code" => "lv"),
            array("language_name" => "Lingala - lingála", "code" => "ln"),
            array("language_name" => "Lithuanian - lietuvių", "code" => "lt"),
            array("language_name" => "Macedonian - македонски", "code" => "mk"),
            array("language_name" => "Malay - Bahasa Melayu", "code" => "ms"),
            array("language_name" => "Malayalam - മലയാളം", "code" => "ml"),
            array("language_name" => "Maltese - Malti", "code" => "mt"),
            array("language_name" => "Marathi - मराठी", "code" => "mr"),
            array("language_name" => "Mongolian - монгол", "code" => "mn"),
            array("language_name" => "Nepali - नेपाली", "code" => "ne"),
            array("language_name" => "Norwegian - norsk", "code" => "no"),
            array("language_name" => "Norwegian Bokmål - norsk bokmål", "code" => "nb"),
            array("language_name" => "Norwegian Nynorsk - nynorsk", "code" => "nn"),
            array("language_name" => "Occitan", "code" => "oc"),
            array("language_name" => "Oriya - ଓଡ଼ିଆ", "code" => "or"),
            array("language_name" => "Oromo - Oromoo", "code" => "om"),
            array("language_name" => "Pashto - پښتو", "code" => "ps"),
            array("language_name" => "Persian - فارسی", "code" => "fa"),
            array("language_name" => "Polish - polski", "code" => "pl"),
            array("language_name" => "Portuguese - português", "code" => "pt"),
            array("language_name" => "Portuguese (Brazil) - português (Brasil)", "code" => "pt-BR"),
            array("language_name" => "Portuguese (Portugal) - português (Portugal)", "code" => "pt-PT"),
            array("language_name" => "Punjabi - ਪੰਜਾਬੀ", "code" => "pa"),
            array("language_name" => "Quechua", "code" => "qu"),
            array("language_name" => "Romanian - română", "code" => "ro"),
            array("language_name" => "Romanian (Moldova) - română (Moldova)", "code" => "mo"),
            array("language_name" => "Romansh - rumantsch", "code" => "rm"),
            array("language_name" => "Russian - русский", "code" => "ru"),
            array("language_name" => "Scottish Gaelic", "code" => "gd"),
            array("language_name" => "Serbian - српски", "code" => "sr"),
            array("language_name" => "Serbo - Croatian", "code" => "sh"),
            array("language_name" => "Shona - chiShona", "code" => "sn"),
            array("language_name" => "Sindhi", "code" => "sd"),
            array("language_name" => "Sinhala - සිංහල", "code" => "si"),
            array("language_name" => "Slovak - slovenčina", "code" => "sk"),
            array("language_name" => "Slovenian - slovenščina", "code" => "sl"),
            array("language_name" => "Somali - Soomaali", "code" => "so"),
            array("language_name" => "Southern Sotho", "code" => "st"),
            array("language_name" => "Spanish - español", "code" => "es"),
            array("language_name" => "Spanish (Argentina) - español (Argentina)", "code" => "es-AR"),
            array("language_name" => "Spanish (Latin America) - español (Latinoamérica)", "code" => "es-419"),
            array("language_name" => "Spanish (Mexico) - español (México)", "code" => "es-MX"),
            array("language_name" => "Spanish (Spain) - español (España)", "code" => "es-ES"),
            array("language_name" => "Spanish (United States) - español (Estados Unidos)", "code" => "es-US"),
            array("language_name" => "Sundanese", "code" => "su"),
            array("language_name" => "Swahili - Kiswahili", "code" => "sw"),
            array("language_name" => "Swedish - svenska", "code" => "sv"),
            array("language_name" => "Tajik - тоҷикӣ", "code" => "tg"),
            array("language_name" => "Tamil - தமிழ்", "code" => "ta"),
            array("language_name" => "Tatar", "code" => "tt"),
            array("language_name" => "Telugu - తెలుగు", "code" => "te"),
            array("language_name" => "Thai - ไทย", "code" => "th"),
            array("language_name" => "Tigrinya - ትግርኛ", "code" => "ti"),
            array("language_name" => "Tongan - lea fakatonga", "code" => "to"),
            array("language_name" => "Turkish - Türkçe", "code" => "tr"),
            array("language_name" => "Turkmen", "code" => "tk"),
            array("language_name" => "Twi", "code" => "tw"),
            array("language_name" => "Ukrainian - українська", "code" => "uk"),
            array("language_name" => "Urdu - اردو", "code" => "ur"),
            array("language_name" => "Uyghur", "code" => "ug"),
            array("language_name" => "Uzbek - o‘zbek", "code" => "uz"),
            array("language_name" => "Vietnamese - Tiếng Việt", "code" => "vi"),
            array("language_name" => "Walloon - wa", "code" => "wa"),
            array("language_name" => "Welsh - Cymraeg", "code" => "cy"),
            array("language_name" => "Western Frisian", "code" => "fy"),
            array("language_name" => "Xhosa", "code" => "xh"),
            array("language_name" => "Yiddish", "code" => "yi"),
            array("language_name" => "Yoruba - Èdè Yorùbá", "code" => "yo"),
            array("language_name" => "Zulu - isiZulu", "code" => "zu")
        );
        // insert array language
        // foreach ($languages_list as $language) {
        //     $this->Curd_model->insert('tbl_languages', $language);
        // }

        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('LanguageName', 'Language Name', 'trim|strip_tags|required');
        $this->form_validation->set_rules('LanguageCode', 'Language Code', 'trim|strip_tags|required');
        if ($this->form_validation->run() == false) {
            $data['title'] = 'Language Add';
            $this->load->view('admin/language', $data);
        } else {

            $date = [
                'language_name' => $this->security->xss_clean($this->input->post('LanguageName')),
                'code' => $this->security->xss_clean($this->input->post('LanguageCode')),
            ];

            if ($this->Curd_model->insert('tbl_languages', $date) == false) {
                $array_msg = array('msg' => 'Server Error!', 'icon' => 'error');
                $this->session->set_flashdata($array_msg);
                redirect(base_url('admin/language/add'));
            } else {
                $array_msg = array('msg' => 'Successfully Create!', 'icon' => 'success');
                $this->session->set_flashdata($array_msg);
                redirect(base_url('admin/language/list'));
            }
        }
    }


    public function delete()
	{
		$id = $this->input->post('delete_id');
		$array = $this->Curd_model->Select('tbl_languages',['id'=>$id]);
		if (empty($array)) {
			echo json_encode(array("statusCode" => 201, "msg" => 'Data not found'));
		}
		if ($this->Curd_model->Delete('tbl_languages',['id'=>$id]) == false) {
			echo json_encode(array("statusCode" => 201, "msg" => 'Server Error!'));
		} else {
			echo json_encode(array("statusCode" => 200, "msg" => 'Deleted Successfully!'));
		}
	}


}
