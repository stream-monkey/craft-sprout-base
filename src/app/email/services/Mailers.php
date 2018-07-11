<?php

namespace barrelstrength\sproutbase\app\email\services;

use barrelstrength\sproutbase\app\email\base\Mailer;

use barrelstrength\sproutbase\app\email\events\RegisterMailersEvent;
use barrelstrength\sproutbase\SproutBase;
use barrelstrength\sproutbase\app\email\events\RegisterSendEmailEvent;
use craft\base\Component;


use craft\mail\Message;
use Craft;
use craft\elements\User;
use yii\base\Event;
use yii\base\Exception;

class Mailers extends Component
{
    const EVENT_REGISTER_MAILER_TYPES = 'defineSproutEmailMailers';
    const ON_SEND_EMAIL = 'onSendEmail';
    const ON_SEND_EMAIL_ERROR = 'onSendEmailError';

    protected $mailers;

    /**
     * @return Mailer[]
     */
    public function getMailers()
    {
        $event = new RegisterMailersEvent([
            'mailers' => []
        ]);

        $this->trigger(self::EVENT_REGISTER_MAILER_TYPES, $event);

        $eventMailers = $event->mailers;

        $mailers = [];

        if (!empty($eventMailers)) {
            foreach ($eventMailers as $eventMailer) {
                $namespace = get_class($eventMailer);
                $mailers[$namespace] = $eventMailer;
            }
        }

        return $mailers;
    }

    /**
     * @param null $name
     *
     * @return Mailer
     * @throws Exception
     */
    public function getMailerByName($name = null)
    {
        $this->mailers = $this->getMailers();

        $mailer = $this->mailers[$name] ?? null;

        if (!$mailer) {
            throw new Exception(Craft::t('sprout-base', 'Mailer not found: {mailer}', [
                'mailer' => $name
            ]));
        }

        return $mailer;
    }

    public function includeMailerModalResources()
    {
        $mailers = SproutBase::$app->mailers->getMailers();

        if (count($mailers)) {
            /**
             * @var $mailer Mailer
             */
            foreach ($mailers as $mailer) {
                $mailer->includeModalResources();
            }
        }
    }
}