
/**
 * Created by Philippe Le Van.
 * Date: 15/02/13
 */
namespace <<proxyNameSpace>>;

use Kitpages\ChainBundle\Proxy\ProxyInterface;
use Kitpages\ChainBundle\Step\StepEvent;
use Kitpages\ChainBundle\KitpagesChainEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * This class is a proxy around a step method.
 * This proxy adds the following methods :
 * -
 *
 * @example
 */
class <<shortClassName>>
    extends <<originalClassName>>
    implements ProxyInterface
{


    ////
    // overidden methods
    ////
    public function execute(StepEvent $event = null)
    {
        if ($event == null) {
            $event = new StepEvent();
        }
        $event->setReturnValue(null);
        $this->__chainProxyEventDispatcher->dispatch(KitpagesChainEvents::ON_PROCESSOR_EXECUTE, $event);
        if (!$event->isDefaultPrevented()) {
            $event->setReturnValue(parent::execute($event));
        }
        $this->__chainProxyEventDispatcher->dispatch(KitpagesChainEvents::AFTER_PROCESSOR_EXECUTE, $event);
        return $event;
    }

    ////
    // added methods
    ////
    private $__chainProxyEventDispatcher = null;
    public function __chainProxySetEventDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->__chainProxyEventDispatcher = $dispatcher;
    }
}
