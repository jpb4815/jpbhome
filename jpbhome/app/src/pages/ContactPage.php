<?php
namespace {

    use SilverStripe\Forms\TextField;
    use SilverStripe\Forms\TextareaField;

    class ContactPage extends Page
    {
        private static $db = [
            'To' => 'Varchar(255)',
            'Subject' => 'Varchar(255)',
            'IntroText' => 'Text',
            'SuccessMessage' => 'Text',
        ];

        public function getCMSFields()
        {
            $fields = parent::getCMSFields();

            $fields->addFieldsToTab('Root.Form', [
                TextField::create('To', _t('ContactPage.To', 'Send contact form to'))
                    ->setDescription('Comma-separate multiple email addresses'),
                TextField::create('Subject', _t('ContactPage.Subject', 'Email subject')),
                TextareaField::create('IntroText', _t('ContactPage.IntroText', 'Email intro text')),
                TextareaField::create('SuccessMessage', _t('ContactPage.SuccessMessage', 'Form success message'))
            ]);
            $fields->removeByName('Sidebar');
            return $fields;
        }

        public function getToEmailAddresses()
        {
            return array_map('trim', explode(',', $this->To));
        }

        public function validate()
        {
            $result = parent::validate();

            if (!$this->To) {
                $result->addFieldError('To', 'You must specify at least one recipient for the contact form.');
            } else {
                $emails = $this->getToEmailAddresses();

                $invalid_emails = array_filter($emails, function ($email) {
                    return !filter_var($email, FILTER_VALIDATE_EMAIL);
                });

                if (count($invalid_emails) > 0) {
                    $result->addFieldError('To', 'Please verify that each recipient is a valid email address.');
                }
            }

            return $result;
        }
    }
}
