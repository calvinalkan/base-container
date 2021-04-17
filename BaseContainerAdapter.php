<?php
	
	namespace SniccoAdapter;
	
    use Contracts\ContainerAdapter;
    use Illuminate\Container\Container;
	use Illuminate\Contracts\Container\Container as IlluminateContainer;
	
	class BaseContainerAdapter implements ContainerAdapter {
		
		/** @var Container */
		private $container;
		
		private $name = 'illuminate_container';
		
		public function __construct( IlluminateContainer $container = NULL ) {
			
			$this->container = $container ?? new Container();
		}

		public function offsetExists( $offset ) {
			
			return $this->container->bound( $offset );
			
		}
		
		public function offsetGet( $offset ) {
			
			return $this->container->make( $offset );
			
		}
		
		public function offsetSet( $offset, $value ) {
			
			// Wrap primitives in a closure.
			if ( ! $value instanceof \Closure && ! is_object( $value ) ) {
				
				$value = function () use ( $value ) {
					return $value;
				};
				
			}
			
			if ( $value instanceof \Closure ) {
				
				
				$this->container->singleton( $offset, $value );
				
				return;
				
				
			}
			
			if ( is_object( $value ) ) {
				
				$this->container->instance( $offset, $value );
				
				return;
				
			}
			
			
		}
		
		public function offsetUnset( $offset ) {
			
			unset( $this->container->bindings[ $offset ], $this->instances[ $offset ], $this->resolved[ $offset ] );
			
			
		}
		
		public function make( $abstract, array $parameters = [] ): object {
			
			return $this->container->make( $abstract, $parameters );
			
		}
		
		public function swapInstance( $abstract, $concrete ) {
			
			$this->instance( $abstract, $concrete );
			
		}
		
		public function instance( $abstract, $instance ) {
			
			$this->container->instance( $abstract, $instance );
			
		}
		
		public function call( $callable, array $parameters = [] ) {
			
			return $this->container->call( $callable, $parameters);
			
		}


        public function bind($abstract, $concrete)
        {
            $this->container->bind($abstract, $concrete );
        }

        public function singleton($abstract, $concrete)
        {
            $this->container->singleton($abstract, $concrete);
        }

    }