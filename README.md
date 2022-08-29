# Description
Akana is a PHP framework used to create restful API easyly and fast. 

# Documentation
## routers declaration
- /: ControllerClass
- /(id:int)/: ControllerClass
- /(name:str)/: ControllerClqss

## models
`
class Model extends ORM {
  public $field;

  public function __construct() {
    $this->field = Column::integer();
  }
}
`
- Column::integer(int $limit, int $default, bool $nullable)
- Column::string(int $limit, string $default, bool $nullable)
- Column::text(string $default, bool $nullable)
- Column::boolean(string $default, bool $nullable)
- Column::date(bool $now_as_default, bool $nullable)
- Column::datetime(bool $now_as_default, bool $nullable)

# TODO
- [ ] commands: add-resource
- [ ] check if all resources in settings.yaml has folder: controller.php, routers.yaml
- [ ] write a documentation
- [ ] use sqlite as default db
- [ ] column choice field