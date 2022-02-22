<?php
/**
 * //@author SMSBill
 * //Класс SMSBill_getpassword используется для предоставления более гибкого способа общения с сервером SMSBill для 
 * //оказания услуги "Получи Пароль".
 * 
 * //Пример использования:
 * 
 * //$smsbill = new SMSBill_getpassword();
 * //$smsbill->setServiceId(12345); //изменить на свое ID доступное в Личном кабинете
 * //$smsbill->useEncoding('windows-1251');
 * //$smsbill->useHeader('yes');
 * //$smsbill->useJQuery('yes');
 * //$smsbill->useLang('ru');
 * //echo $smsbill->getForm();
 *
 * //Вышепредставленных строчек достаточно для полноценной работы скрипта.
 * //Рассмотрим назначение методов данного класса:
 *
 * //Устанавка ID услуги. Это обязательный параметр. ID усуги дотсупно в Личном кабинете в разделе "Услуги".
 * $smsbill = new SMSBill_getpassword();
 * $smsbill->setServiceId(12345); 
 * 
 * //getForm - основной метод который генерирует код платежной формы:
 * echo $smsbill->getForm();
 *
 * //Опциональные параметры (необязательные для использования):
 *
 * //useCss - строковое значение, позволяющее вам прописать свой CSS-файл, который будет использоваться для показа 
 * //платежной формы. Вам необходимо указать URL, по которому находится ваш CSS-файл:
 * $smsbill->useCss('http://www.example.com/form.css');
 * 
 * //useEncoding - позволяет переопределить кодировку для корректного отображения формы на вашей страницы. По 
 * //умолчанию используется кодировка UTF-8, однако вы можете использовать любую другую кодировку (например CP1251 для
 * //страниц, написанных в кодировке Windows-1251).
 * $smsbill->useEncoding('CP1251');
 *
 * //useHeader - значения 'yes','no'. Позволяет отключить вывод заголовка <html><head></head></html> и тегов <body></body>.
 * //Эта возможность важна при выводе платежной формы в теле существующей страницы - в этом случае заголовок нужно отключить.
 * //По умолчанию заголовок выводится, т.к. предполагается, что платежная форма будет использована как отдельная страница.
 * $smsbill->useHeader('no');
 *
 * //useJQuery - значения 'yes','no'. Позволяет отключить использоввания библиотеки jQuery если она уже подключена на сайте
 * //и возникают конфликты с платежной формой. По умолчанию библиотека подключена.
 * $smsbill->useJQuery('no');
 *
 * //useLang - значения 'ru','ua','en','lt'. Позволяет выбарть язык формы по умолчанию. Если не указывать значение, то форма
 * //будет показана на русском языке.
 * $smsbill->useLang('ua');
 * 
 * //checkPassword - проверяет, активен ли такой пароль для выбранной услуги. Используется только в случае необходиомсти. 
 * //Метод возвращает true в случе успешной проверки пароля, либо false в противном случае.
 * //Данный метод используется когда нужно выполнить различные действия в случае верного и неверного пароля. 
 * //Вариант применения может быть такой (при этом используется предыдущий метод getForm()):
 *
 * if (isset($_REQUEST['smsbill_password'])) {
 *     if (!$smsbill->checkPassword($_REQUEST['smsbill_password'])) {
 *			//введен не верный пароль
 *			echo 'Введенный пароль не верный вернитесь назад и попробуйте еще раз';
 *		}else{ 
 *			//введен верный пароль
 *			//выполняются алгоритмы в случае верного пароля
 *		}
 * }else{
 *		//показать платежную форму т.к. пароль еще не был введен
 *		echo $smsbill->getForm();
 * }
 * 
 * 
 */

class SMSBill_getpassword {
	
	private $_serviceId;
	private $_formUrl = 'http://form.smsbill.com.ua/';
	private $_apiUrl = 'http://api.smsbill.com.ua/';
	
	//form settings
	private $_useCss = false;
	private $_useEncoding = 'UTF-8';
	private $_requestResult = '';
	private $_useHeader = 'yes';
	private $_useJQuery = 'yes';
	private $_useLang = 'ru';
	
	public function setServiceId($serviceId)
	{
		$this->_serviceId = $serviceId;
	}
	
	public function useCss($cssUrl)
	{
		$this->_useCss = $cssUrl;
	}
	
	public function useEncoding($encoding)
	{
		$this->_useEncoding = $encoding;
	}
	
	public function useHeader($header)
	{
		$this->_useHeader = $header;	
	
	}

	public function useJQuery($jquery)
	{
		$this->_useJQuery = $jquery;	
	
	}

	public function useLang($lang)
	{
		$this->_useLang = $lang;
	}

	public function getForm()
	{
		if (empty($this->_serviceId)) {
			die('ServiceId Not Setted');
		}
		$url = $this->_formUrl . 'getform.php?id=' . $this->_serviceId;
		$url .= '&encoding=' . $this->_useEncoding;
		$url .= '&header=' . $this->_useHeader;
		$url .= '&jquery=' . $this->_useJQuery;
		if (empty($_REQUEST['lang'])){
		$url .= '&lang='.$this->_useLang;}else{
		$url .= '&lang='.$_REQUEST['lang'];}
		$url .= '&ip=' . $_SERVER['REMOTE_ADDR'];
		if ($this->_useCss) {
			$url .= '&css=' . urlencode($this->_useCss);
		}
		$result = $this->_getUrl($url);
		return $result;
	}

	public function checkPassword($password)
	{
		if (empty($this->_serviceId)) {
			die('ServiceId Not Setted');
		}
		$url = $this->_apiUrl . 'check.php?id=' . $this->_serviceId;
		$url .= '&ip=' . $_SERVER['REMOTE_ADDR'];
		$url .= '&password=' . $password;
		$result = $this->_getUrl($url);
		if (!$result) {
			return false; 
		}
		if ($result == 'ok') {
			return true;
		}
		return false;
	}


	private function _getUrl($url)
	{
		if (ini_get('allow_url_fopen')) {
			$this->_requestResult = file_get_contents($url); 
			return $this->_requestResult;
		}
		if (extension_loaded('curl')) {
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$this->_requestResult = @curl_exec($curl);
			curl_close($curl);
			return $this->_requestResult;
		}
		die('allow_url_fopen is false and cURL extension is not loaded. No way to communicate with SMSBill server.');
		
		
	}
}