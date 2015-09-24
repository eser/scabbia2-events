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

use Scabbia\Events\Delegate;

/**
 * Events
 *
 * @package     Scabbia\Events
 * @author      Eser Ozvataf <eser@ozvataf.com>
 * @since       1.0.0
 */
class Events
{
    /** @type array      event delegates */
    public $delegates = [];
    /** @type array      event depth */
    public $eventDepth = [];
    /** @type bool       indicates the event manager is currently disabled or not */
    public $disabled = false;


    /**
     * Dispatches an event
     *
     * @param string     $uEvent     name of the event
     * @param array      $uEventArgs arguments for the event
     *
     * @return void
     */
    public function dispatch($uEvent, ...$uEventArgs)
    {
        if ($this->disabled) {
            return;
        }

        if (!isset($this->delegates[$uEvent])) {
            return;
        }

        $this->eventDepth[] = [$uEvent, $uEventArgs];
        $this->delegates[$uEvent]->invoke(...$uEventArgs);
        array_pop($this->eventDepth);
    }

    /**
     * Subscribes a callback method to specified event
     *
     * @param string   $uEvent    event
     * @param callable $uCallback callback
     * @param mixed    $uState    state object
     * @param null|int $uPriority priority
     *
     * @return void
     */
    public function on($uEvent, $uCallback, $uState = null, $uPriority = null)
    {
        if (!isset($this->delegates[$uEvent])) {
            $this->delegates[$uEvent] = new Delegate();
        }

        $this->delegates[$uEvent]->subscribe($uCallback, $uState, $uPriority);
    }
}
