<?php declare(strict_types=1);

namespace JK\Elasticlog\Log\Messages\Jobs\Tasks;

use JK\Elasticlog\Log\Message;

final class CronTaskPing extends Message
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct();

        $this->name = strtolower(
            basename(str_replace('\\', '/', $name))
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'task' => $this->name,
        ];
    }
}
