// Copyright 2010 Google Inc. All Rights Reserved.

/**
 * @fileoverview Toolbar class for primary toolbar.
 *
 * @author jeremydw (Jeremy Weinstein)
 */

/**
 * Initializes Toolbar
 * @param {string} menuId The id of the menu to toolbarize.
 * @constructor Toolbar
 */
var Toolbar = function(menuId) {
  this.mainMenu = gweb.dom.getElement(menuId);
  this.init_();
};

/**
 * Initializes mouse events for toolbar.
 * @private
 */
Toolbar.prototype.init_ = function() {
  gweb.array.forEach(gweb.dom.query('li', this.mainMenu), function(menuItem) {
    gweb.events.listen(menuItem, 'mouseover', function(event) {
      gweb.dom.classes.add(menuItem, 'hover');
    }, false, this);
    gweb.events.listen(menuItem, 'mouseout', function(event) {
      gweb.dom.classes.remove(menuItem, 'hover');
    }, false, this);
  }, this);

  this.subMenus = gweb.dom.query('ul', this.mainMenu);
  gweb.array.forEach(this.subMenus, gweb.bind(function(subMenu) {
    gweb.dom.classes.add(gweb.dom.query('a', subMenu.parentNode)[0], 'drop');
    gweb.events.listen(subMenu.parentNode, 'mouseover', function(event) {
      this.show(subMenu);
    }, false, this);
    gweb.events.listen(subMenu.parentNode, 'mouseout', function(event) {
      this.hide(subMenu);
    }, false, this);
  }, this));
};

/**
 * Displays submenu.
 * @param {object} subMenu Submenu for the nav being selected.
 */
Toolbar.prototype.show = function(subMenu) {
  subMenu.style.top = '33px';
  subMenu.style.left = subMenu.parentNode.offsetLeft + 'px';
  subMenu.style.width = '200px';
  subMenu.style.display = 'block';
};

/**
 * Hides a pulldown menu.
 * @param {HTMLElement} subMenu The dom element corresponding to the pulldown.
 */
Toolbar.prototype.hide = function(subMenu) {
  subMenu.style.display = 'none';
};

