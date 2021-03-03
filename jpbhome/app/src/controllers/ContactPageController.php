<?php

namespace {
    use Postmark\Models\PostmarkException;
    use Postmark\PostmarkClient;
    use ReCaptcha\ReCaptcha;
    use SilverStripe\Forms\EmailField;
    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\Form;
    use SilverStripe\Forms\FormAction;
    use SilverStripe\Forms\LiteralField;
    use SilverStripe\Forms\RequiredFields;
    use SilverStripe\Forms\TextareaField;
    use SilverStripe\Forms\TextField;
    use SilverStripe\ORM\ValidationException;
    use SilverStripe\ORM\ValidationResult;
    use SilverStripe\View\Requirements;

    class ContactPageController extends PageController
    {
        private static $allowed_actions =[
            'ContactForm',
        ];

        public function init()
        {
            parent::init();

            Requirements::javascript('javascript/parsley.min.js');
            Requirements::javascript('javascript/contact.js');
        }

        public function ContactForm()
        {
            Requirements::javascript('https://www.google.com/recaptcha/api.js'); // Google Recaptcha API
            Requirements::javascript('javascript/contact.js');

            return Form::create(
                $this,
                'ContactForm',
                FieldList::create(
                    TextField::create('Name', _t('ContactForm.Name', 'Your Name')),
                    EmailField::create('Email', _t('ContactForm.Email', 'Your Email'))
                        ->setAttribute('data-parsley-type', 'email'),
                    TextareaField::create('Message', _t('ContactForm.Message', 'Your Message')),
                    LiteralField::create(
                        'Recaptcha',
                        '<div class="form-group"><div id="recaptcha" class="g-recaptcha" data-badge="inline" data-sitekey="' . RECAPTCHA_API_PUBLIC_KEY . '" data-size="normal" data-callback="onRecaptchaSubmit"></div></div>'
                    )
                ),
                FieldList::create(
                    FormAction::create('doContactSubmit', "Submit")->setUseButtonTag(true)->addExtraClass("btn btn-default")
                ),
                RequiredFields::create([
                    'Name',
                    'Email',
                    'Message',
                ])
            );
        }

        public function doContactSubmit($data, $form)
        {
            $session = $this->getRequest()->getSession();
            $validation_result = ValidationResult::create();

            // Check if name is set
            if (!$data['Name']) {
                $validation_result->addFieldError('Name', 'Name is required.');
            }

            // Check if email is set
            if (!$data['Email']) {
                $validation_result->addFieldError('Email', 'Email is required.');
            } elseif (!filter_var($data['Email'], FILTER_VALIDATE_EMAIL)) {
                $validation_result->addFieldError('Email', 'Please enter a valid email address.');
            }

            // Check if message is set
            if (!$data['Message']) {
                $validation_result->addFieldError('Message', 'Message is required.');
            }

            if (!$validation_result->isValid()) {
                $validation_result->addMessage('Please correct the errors below.');
                $session->set("FormInfo.{$form->FormName()}.data", $data);
                throw new ValidationException($validation_result);
            }

            // Check if we're dealing with a robot
            $recaptcha = new ReCaptcha(RECAPTCHA_API_SECRET_KEY);
            $response = $recaptcha->verify($data['g-recaptcha-response'], $this->getRequest()->getIP());
            if (!$response->isSuccess()) {
                $validation_result->addMessage('Please confirm you are not a robot.');
                throw new ValidationException($validation_result);
            }

            $message = '';
            if ($this->IntroText) {
                $message .= "{$this->IntroText}<br /><br />";
            }

            $message .= "<strong>Name:</strong> {$data['Name']}<br />";
            $message .= "<strong>Email:</strong> {$data['Email']}<br />";
            $message .= "<strong>Message:</strong> <br>{$data['Message']}<br />";

            $sent = false;
            try {
                $postmark_client = new PostmarkClient(POSTMARKAPP_API_KEY);
                $response = $postmark_client->sendEmail(
                    POSTMARKAPP_MAIL_FROM_ADDRESS,
                    $this->To,
                    $this->Subject ?: 'New Contact Form Submission',
                    $message,
                    null,
                    null,
                    true,
                    "{$data['Name']} <{$data['Email']}>"
                );
                $sent = true;
            } catch (PostmarkException $e) {
                error_log("Caught PostmarkException while sending ContactForm email: $e");
            } catch (Exception $e) {
                error_log("Caught general Exception while sending ContactForm email: $e");
            }

            if (!$sent) {
                $validation_result->addMessage('We were unable to process your submission. Please try again.');
                $session->set("FormInfo.{$form->FormName()}.data", $data);
                throw new ValidationException($validation_result);
            }

            $form->sessionMessage($this->SuccessMessage, ValidationResult::TYPE_GOOD);

            return $this->redirectBack();
        }
    }

}
