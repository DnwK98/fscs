<?php

namespace Tests\Unit;

use App\Clients\CsDocker\Builders\DockerContainerBuilder;
use Tests\TestCase;

class DockerContainerBuilderTest extends TestCase
{
    public function testCreatesValidCommandWithoutConfiguration()
    {
        $builder = new DockerContainerBuilder();
        $command = $builder->getCommand();

        $this->assertEquals(
            'docker run -d --name example hello-world',
            $command
        );
    }

    public function testAddEnvProperly()
    {
        $builder = new DockerContainerBuilder();
        $builder->addEnv('e1', 'v1');
        $builder->addEnv('e2', 'v2');
        $command = $builder->getCommand();

        $this->assertEquals(
            'docker run -d -e e1="v1" -e e2="v2" --name example hello-world',
            $command
        );
    }
    public function testEnvValuesAreEscaped()
    {
        $builder = new DockerContainerBuilder();
        $builder->addEnv('e1', 'key:"value"');

        $command = $builder->getCommand();

        $this->assertEquals(
            'docker run -d -e e1="key:\"value\"" --name example hello-world',
            $command
        );

    }

    public function testMapPortProperly()
    {
        $builder = new DockerContainerBuilder();
        $builder->addPortMapping(1, 1);
        $builder->addPortMapping(3, 5);
        $command = $builder->getCommand();

        $this->assertEquals(
            'docker run -d -p 1:1/tcp -p 1:1/udp -p 3:5/tcp -p 3:5/udp --name example hello-world',
            $command
        );
    }

    public function testSetsNameAndImageProperly()
    {
        $builder = new DockerContainerBuilder();
        $builder->setContainerName('Test_name');
        $builder->setImage('image-name');
        $command = $builder->getCommand();

        $this->assertEquals(
            'docker run -d --name Test_name image-name',
            $command
        );
    }

    public function testBuildsCommandInAlphabeticOrder()
    {
        $builder = new DockerContainerBuilder();
        $builder->addEnv('zz', 'v1');
        $builder->addEnv('aa', 'v2');
        $builder->addEnv('cc', 'v3');
        $command = $builder->getCommand();

        $this->assertEquals(
            'docker run -d -e aa="v2" -e cc="v3" -e zz="v1" --name example hello-world',
            $command
        );
    }
}
