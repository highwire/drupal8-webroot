# Adnuntius
  
The Adnuntius module provides a block and field, that render
[Adnuntius](https://www.adnuntius.com/) ads.

## Features

* Add Adnuntius ads using a Drupal block plugin
* Attach Adnuntius ads to any fieldable entity using a Drupal Field
* Choose between the following ads delivery methods
  * Div
  * iFrame
* Make the delivery method configurable on per entity base (optional)

## Installation

1. Download module as usual.
2. Enable the module.

## Configuration
1. Configure the module at `/admin/structure/services/adnuntius`.

### Block
Add a block and set configure the to-be-rendered zone and delivery method.

### Field
Add a field to your designated content entity. Choose the delivery method in
the field display configuration.

If you like to let your content editors choose the invocation method on *per
entity base* you can enable the checkbox in the field's configuration. You
can limit the available methods in the "Manage fields" configuration.

## Requirements

* Block (Drupal core)
* Field (Drupal core)
