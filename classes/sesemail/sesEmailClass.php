<?php
require_once(INCLUDES. 'aws/aws-autoloader.php');

use Aws\Ses\SesClient;
use Aws\Exception\AwsException;

class SESEmail {

	private $sesClient;
	public $subject, $sender, $recipients, $htmlBody, $bcc, $charset, $replyto, $region, $key, $secret;
	function __construct( ){

	}

	public function sendEmail(){
		$this->sesClient = new SesClient([
			'version' => '2010-12-01',
			'region'  => $this->region,
			'credentials' => [
				'key' => $this->key,
				'secret' => $this->secret
			]
		]);

		try {
			$result = $this->sesClient->sendEmail([
				'Destination' => [
					'ToAddresses' => $this->recipients,
					'BccAddresses' => $this->bcc,
				],
				'ReplyToAddresses' => [$this->replyto],
				'Source' => $this->sender,
				'Message' => [
					'Body' => [
						'Html' => [
							'Charset' => $this->charset,
							'Data' => $this->htmlBody,
						],
					],
					'Subject' => [
						'Charset' => $this->charset,
						'Data' => $this->subject,
					],
				],
			]);
			$messageId = $result['MessageId'];
			echo("Email sent! Message ID: $messageId"."\n");
		} catch (AwsException $e) {
			// output error message if fails
			echo $e->getMessage();
			echo("The email was not sent. Error message: ".$e->getAwsErrorMessage()."\n");
			echo "\n";
		}
	}
}
