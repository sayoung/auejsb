# Patches

## menu_item_role_access (PHP 8.4)

File: `menu_item_role_access-8.x-2.4-php84-nullable.patch`

Fixes deprecation:
`Implicitly marking parameter $items as nullable is deprecated`

### Apply on server (module already installed)

```bash
cd /home/auejsb/public_html/auejsb/web/modules/contrib/menu_item_role_access
# or wherever the module lives, e.g. modules/menu_item_role_access
patch -p1 < /path/to/menu_item_role_access-8.x-2.4-php84-nullable.patch
drush cr
```

### Manual one-line fix (same effect)

In `menu_item_role_access.module` ~line 98, change:

```php
FieldItemListInterface $items = NULL
```

to:

```php
?FieldItemListInterface $items = NULL
```

Also update the constructor in `src/MenuItemRoleAccessLinkTreeManipulator.php`:

```php
ModuleHandlerInterface $module_handler = NULL
```

to:

```php
?ModuleHandlerInterface $module_handler
```

Upstream: https://www.drupal.org/project/menu_item_role_access/issues/3613215
