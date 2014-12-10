<?php

namespace MBHS\Bundle\ClientBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class ClientChannelManagerCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('mbhs:client:channelmanager')
            ->setDescription('Set channel manager count to a client')
            ->addArgument('query', InputArgument::REQUIRED, 'Search query (title, url, email or Id)')
            ->addArgument('channelManagerCount', InputArgument::REQUIRED, 'Channel manager count')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dm = $this->getContainer()->get('doctrine_mongodb')->getManager();
        $query = trim($input->getArgument('query'));

        $qb = $dm->getRepository('MBHSClientBundle:Client')->createQueryBuilder('q');

        $client = $qb->addOr($qb->expr()->field('id')->equals($query))
            ->addOr($qb->expr()->field('title')->equals($query))
            ->addOr($qb->expr()->field('url')->equals($query))
            ->addOr($qb->expr()->field('email')->equals($query))
            ->limit(1)
            ->getQuery()
            ->getSingleResult();
        ;

        if(!$client) {
            $output->writeln('<error>Client not found! Query: ' . $input->getArgument('query') . '</error>');

            return false;
        }

        $client->setChannelManagerCount($input->getArgument('channelManagerCount'));
        $dm->persist($client);
        $dm->flush();

        $output->writeln('<info>Channel manager count saved. Client: ' . $client->getTitle() . ' (#' . $client->getId() . '). Channel manager count: ' . $client->getChannelManagerCount() . '</info>');
    }
    /**
     * @see Command
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('query')) {
            $arg = $this->getHelper('dialog')->askAndValidate(
                $output,
                '<question>Please enter a search query (client title, url, email or Id):</question>',
                function($arg) {
                    if (empty($arg)) {
                        throw new \Exception('Query can not be empty');
                    }
                    return $arg;
                }
            );
            $input->setArgument('query', $arg);
            unset($arg);
        }
        if (!$input->getArgument('channelManagerCount')) {
            $arg = $this->getHelper('dialog')->askAndValidate(
                $output,
                '<question>Please enter a channel manager count:</question>',
                function($arg) {
                    if ($arg == null) {
                        throw new \Exception('Channel manager count can not be empty');
                    }

                    return $arg;
                }
            );
            $input->setArgument('channelManagerCount', (int) $arg);
            unset($arg);
        }
    }
}