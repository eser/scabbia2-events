<?php
/**
 * Scabbia2 Events Component
 * http://www.scabbiafw.com/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link        https://github.com/scabbiafw/scabbia2-events for the canonical source repository
 * @copyright   2010-2015 Scabbia Framework Organization. (http://www.scabbiafw.com/)
 * @license     http://www.apache.org/licenses/LICENSE-2.0 - Apache License, Version 2.0
 */

namespace Scabbia\Events;

/**
 * Delegate is an inline members which executes an event-chain execution similar to Events,
 * but designed for object-oriented architecture
 *
 * @package     Scabbia\Events
 * @author      Eser Ozvataf <eser@ozvataf.com>
 * @since       2.0.0
 *
 * @remark SplPriorityQueue could be used, but it doesn't support serialiations.
 */
class Delegate
{
    /** @type array   list of callbacks */
    public $callbacks = null;
    /** @type mixed   stop propagation if false is returned */
    public $stopPropagationWithReturn = true;
    /** @type bool    priorities sorted or not */
    private $prioritiesSorted = true;


    /**
     * Constructs a new delegate in order to assign it to a member
     *
     * @return Delegate a delegate
     */
    public static function assign()
    {
        $tNewInstance = new static();

        return function (/* callable */ $uCallback = null, $uState = null, $uPriority = 10) use ($tNewInstance) {
            if ($uCallback !== null) {
                $tNewInstance->add($uCallback, $uState, $uPriority);
            }

            return $tNewInstance;
        };
    }

    // @codingStandardsIgnoreStart
    /**
     * Unserializes an instance of delegate
     *
     * @param array $uPropertyBag properties set of unserialized object
     *
     * @return Delegate a delegate
     */
    public static function __set_state(array $uPropertyBag)
    {
        $tNewInstance = new static();
        $tNewInstance->callbacks = $uPropertyBag["callbacks"];

        return $tNewInstance;
    }
    // @codingStandardsIgnoreEnd

    /**
     * Constructs a new instance of delegate
     *
     * @return Delegate
     */
    public function __construct()
    {
    }

    /**
     * Subscribes a callback to delegate
     *
     * @param callback  $uCallback  callback method
     * @param mixed     $uState     state object
     * @param null|int  $uPriority  priority level
     *
     * @return void
     */
    public function subscribe(/* callable */ $uCallback, $uState = null, $uPriority = null)
    {
        if ($uPriority === null) {
            $uPriority = 10;
        }

        if ($this->callbacks === null) {
            $this->callbacks = [[$uCallback, $uState, $uPriority]];
        } else {
            $this->callbacks[] = [$uCallback, $uState, $uPriority];
            $this->prioritiesSorted = false;
        }
    }

    /**
     * Invokes the event-chain execution
     *
     * @param array $uParameters execution parameters
     *
     * @return bool whether the propagation is stopped or not
     */
    public function invoke(...$uParameters)
    {
        if ($this->callbacks !== null) {
            if ($this->prioritiesSorted === false) {
                usort($this->callbacks, [$this, 'prioritySort']);
                $this->prioritiesSorted = true;
            }

            foreach ($this->callbacks as $tCallback) {
                if (call_user_func($tCallback[0], ...$uParameters) === false && $this->stopPropagationWithReturn) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Sorts callbacks in order to their priority
     *
     * @param int $uFirst  first array item
     * @param int $uSecond second array item
     *
     * @return int comparision result that indicates which item comes first
     */
    private function prioritySort($uFirst, $uSecond)
    {
        if ($uFirst[2] < $uSecond[2]) {
            return -1;
        }

        if ($uFirst[2] > $uSecond[2]) {
            return 1;
        }

        return 0;
    }
}
