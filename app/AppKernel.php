<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
            new A2lix\I18nDoctrineBundle\A2lixI18nDoctrineBundle(),
            new A2lix\TranslationFormBundle\A2lixTranslationFormBundle(),
            new Asm\TranslationLoaderBundle\AsmTranslationLoaderBundle(),
            new JMS\I18nRoutingBundle\JMSI18nRoutingBundle(),

            new CoreBundle\CoreBundle(),
            new CoreExtraBundle\CoreExtraBundle(),
            new AdminBundle\AdminBundle(),
            new BlogBundle\BlogBundle(),
            new CatalogueBundle\CatalogueBundle(),
            new PaymentBundle\PaymentBundle(),
            new FrontBundle\FrontBundle(),
            new TicketBundle\TicketBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'), true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
