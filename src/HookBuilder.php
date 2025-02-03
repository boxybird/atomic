<?php

declare(strict_types=1);

namespace BoxyBird\Atomic;

final class HookBuilder
{
    private array $hooks = [];

    private array $hooks_used = [];

    public function action(string $hook_name, callable $callback, int $priority = 10, int $accepted_args = 1): self
    {
        $this->setProperties('action', $hook_name, $callback, $priority, $accepted_args);

        return $this;
    }

    public function filter(string $hook_name, callable $callback, int $priority = 10, int $accepted_args = 1): self
    {
        $this->setProperties('filter', $hook_name, $callback, $priority, $accepted_args);

        return $this;
    }

    public function when(callable $callback): void
    {
        if ($callback($this->hooks_used)) {
            $this->run();
        }
    }

    private function setProperties(string $type, string $hook_name, callable $callback, int $priority = 10, int $accepted_args = 1): void
    {
        $this->hooks[][$type][$hook_name] = [
            'callback' => $callback,
            'priority' => $priority,
            'accepted_args' => $accepted_args,
        ];

        $this->hooks_used[] = $hook_name;
    }

    private function run(): void
    {
        foreach ($this->hooks as $_hooks) {
            foreach ($_hooks as $hook_type => $hooks) {
                if ($hook_type === 'action') {
                    foreach ($hooks as $hook_name => $hook) {
                        add_action($hook_name, $hook['callback'], $hook['priority'], $hook['accepted_args']);
                    }
                }

                if ($hook_type === 'filter') {
                    foreach ($hooks as $hook_name => $hook) {
                        add_filter($hook_name, $hook['callback'], $hook['priority'], $hook['accepted_args']);
                    }
                }
            }
        }
    }
}
