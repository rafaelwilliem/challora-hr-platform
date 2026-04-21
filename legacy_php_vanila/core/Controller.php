<?php
/**
 * Base Controller
 */
abstract class Controller {
    protected function render(string $view, array $data = []): void {
        render_view($view, $data);
    }
}
