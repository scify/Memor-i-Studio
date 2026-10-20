<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\File;
use ReflectionClass;
use ReflectionNamedType;
use Tests\TestCase;

class ConstructorDependencyCycleTest extends TestCase {
    /**
     * The container cannot build a class whose constructor dependencies lead back to
     * itself: it recurses until PHP runs out of memory (route:list and every controller
     * that needs such a class fail).
     */
    public function test_application_classes_have_no_constructor_dependency_cycles(): void {
        $graph = [];
        foreach (File::allFiles(app_path()) as $file) {
            $class = 'App\\' . str_replace(['/', '.php'], ['\\', ''], $file->getRelativePathname());
            if (!class_exists($class)) {
                continue;
            }
            $constructor = (new ReflectionClass($class))->getConstructor();
            $graph[$class] = [];
            foreach ($constructor?->getParameters() ?? [] as $parameter) {
                $type = $parameter->getType();
                if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                    $graph[$class][] = $type->getName();
                }
            }
        }

        $cycles = [];
        $state = [];
        $path = [];
        $visit = function (string $class) use (&$visit, &$graph, &$state, &$path, &$cycles): void {
            $state[$class] = 'visiting';
            $path[] = $class;
            foreach ($graph[$class] ?? [] as $dependency) {
                if (($state[$dependency] ?? null) === 'visiting') {
                    $cycles[] = implode(' -> ', [...array_slice($path, array_search($dependency, $path, true)), $dependency]);
                } elseif (!isset($state[$dependency])) {
                    $visit($dependency);
                }
            }
            array_pop($path);
            $state[$class] = 'done';
        };
        foreach (array_keys($graph) as $class) {
            if (!isset($state[$class])) {
                $visit($class);
            }
        }

        $this->assertSame([], $cycles);
    }
}
