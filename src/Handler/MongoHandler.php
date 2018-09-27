<?php

declare(strict_types=1);

namespace Renlife\Profiler\Handler;

use MongoDB\Client;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Writes profiling data to MongoDb.
 */
class MongoHandler extends AbstractHandler
{
    /**
     * @var array
     */
    private $options;

    /**
     * @var Client|null
     */
    private $client;

    /**
     * MongoHandler constructor.
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);
    }

    /**
     * Configures required options for MongoDb connection.
     *
     * @param OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'options' => [],
        ]);
        $resolver->setRequired([
            'uri',
            'collection',
            'db',
        ]);

        $resolver->setAllowedTypes('options', 'array');
        $resolver->setAllowedTypes('uri', 'string');
        $resolver->setAllowedTypes('collection', 'string');
        $resolver->setAllowedTypes('db', 'string');
    }

    /**
     * {@inheritdoc}
     */
    protected function write($data): void
    {
        $collection = $this->getClient()->{$this->options['db']}->{$this->options['collection']};

        $collection->insertOne($data, ['w' => 0]);
    }

    /**
     * @return Client
     */
    private function getClient(): Client
    {
        if (null === $this->client) {
            $this->client = new Client($this->options['uri'], $this->options['options']);
        }

        return $this->client;
    }
}
