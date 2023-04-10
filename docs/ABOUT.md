# About HexaPHP
HexaPHP is a PHP framework that follows the hexagonal architecture pattern. It is designed to make it easy to build maintainable and scalable PHP applications, by providing a simple and consistent structure for organizing code and separating concerns.

## Architecture
HexaPHP is organized into several key components:

### apps
One or more subdirectories that each represent a separate PHP application, containing a Dockerfile, composer.json, and a src directory with the application code.
### core
Common code and infrastructure that is shared across all applications in the monorepo.
### libs
One or more subdirectories that each represent a separate PHP library, containing a composer.json file and a src directory with the library code.

Each application in HexaPHP is built using the hexagonal architecture pattern, which emphasizes separation of concerns and modularity.

HexaPHP follows a monorepo architecture, meaning that all of its components are managed together as a single codebase.

## Goals
The HexaPHP framework is designed to achieve the following goals:

- Simplify development: By providing a consistent structure for organizing code and separating concerns, developers can more easily build and maintain PHP applications.
- Increase code reusability: By organizing code into libraries and following the hexagonal architecture pattern, developers can reduce duplication and increase code reusability.
- Simplify deployment: By using Docker for development and deployment, developers can more easily package and deploy PHP applications to a production environment.
## Contact
If you have any questions or comments about HexaPHP, please contact us at example@example.com.