<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfValidatori18nDate validates DateTime based on Culture
 * packaged by Rafael Goulart <rafaelgou@rgou.net>
 *
 * @package    symfony
 * @subpackage validator
 * @author     logistiker
 * @see        http://trac.symfony-project.org/attachment/ticket/6256/sfValidatorL10nDate.class.php
 */
class sfValidatori18nDate extends sfValidatorDate
{
   /**
    * Configures the current validator.
    *
    * Available options:
    *
    *  * date_format:             A regular expression that dates must match
    *  * with_time:               true if the validator must return a time, false otherwise
    *  * date_output:             The format to use when returning a date (default to Y-m-d)
    *  * datetime_output:         The format to use when returning a date with time (default to Y-m-d H:i:s)
    *  * date_format_error:       The date format to use when displaying an error for a bad_format error
    *                             (use date_format if not provided)
    *  * max:                     The maximum date allowed (as a timestamp)
    *  * min:                     The minimum date allowed (as a timestamp)
    *  * date_format_range_error: The date format to use when displaying an error for min/max (default to d/m/Y H:i:s)
    *  * context:                 The symfony application context
    *
    * Available error codes:
    *
    *  * bad_format
    *  * min
    *  * max
    *
    * @param array $options    An array of options
    * @param array $messages   An array of error messages
    *
    *  @see sfValidatorBase
    */
    protected function configure($options = array(), $messages = array())
    {
        $this->addOption('context', sfContext::getInstance());
        parent::configure($options, $messages);
    }
    /**
     * @see sfValidatorBase
     */
    protected function doClean($value)
    {
        if (is_array($value)) {
            $clean = $this->convertDateArrayToTimestamp($value);
        } else if ($regex = $this->getOption('date_format')) {
            if (!preg_match($regex, $value, $match)) {
                throw new sfValidatorError($this, 'bad_format',
                    array(
                        'value' => $value,
                        'date_format' => $this->getOption('date_format_error') ?
                            $this->getOption('date_format_error') : $this->getOption('date_format'))
                    );
            }
            $clean = $this->convertDateArrayToTimestamp($match);
        } else if (!ctype_digit($value)) {
            $context = $this->getOption('context');
            $i18n = $context->getI18N();
            list($d, $m, $y) = $i18n->getDateForCulture($value, $context->getUser()->getCulture());
            $value = "$y-$m-$d";
            if ($this->getOption('with_time')) {
                list($h, $m) = $i18n->getTimeForCulture($value, $context->getUser()->getCulture());
                $value = "$value $h:$m";
            }
            $clean = strtotime($value);
            if (false === $clean) {
                throw new sfValidatorError($this, 'invalid', array('value' => $value));
            }
        } else {
            $clean = (integer) $value;
        }
        if ($this->hasOption('max') && $clean > $this->getOption('max')) {
            throw new sfValidatorError($this, 'max',
                array(
                    'value' => $value,
                    'max' => date($this->getOption('date_format_range_error'), $this->getOption('max')))
            );
        }
        if ($this->hasOption('min') && $clean < $this->getOption('min')) {
            throw new sfValidatorError($this, 'min',
                array(
                    'value' => $value,
                    'min' => date($this->getOption('date_format_range_error'), $this->getOption('min')))
            );
        }
        return $clean === $this->getEmptyValue() ?
            $clean : date($this->getOption('with_time') ?
                $this->getOption('datetime_output') : $this->getOption('date_output'), $clean);
    }
}
