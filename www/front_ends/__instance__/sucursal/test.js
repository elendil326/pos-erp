


Ext.define("Kitchensink.view.Overlays", {
    extend: "Ext.Container",
    requires: ["Ext.MessageBox", "Ext.ActionSheet", "Ext.picker.Picker"],
    config: {
        padding: 20,
        scrollable: true,
        layout: {
            type: "vbox",
            pack: "center",
            align: "stretch"
        },
        defaults: {
            xtype: "button",
            cls: "demobtn",
            ui: "round",
            margin: "10 0"
        },
        items: [{
            text: "Action Sheet",
            model: false,
            handler: function () {
                if (!this.actions) {
                    this.actions = Ext.Viewport.add({
                        xtype: "actionsheet",
                        items: [{
                            text: "Delete draft",
                            ui: "decline",
                            handler: Ext.emptyFn
                        }, {
                            text: "Save draft",
                            handler: Ext.emptyFn
                        }, {
                            xtype: "button",
                            text: "Cancel",
                            scope: this,
                            handler: function () {
                                this.actions.hide()
                            }
                        }]
                    })
                }
                this.actions.show()
            }
        }, {
            text: "Overlay",
            handler: function () {
                if (!this.overlay) {
                    this.overlay = Ext.Viewport.add({
                        xtype: "panel",
                        modal: true,
                        hideOnMaskTap: true,
                        showAnimation: {
                            type: "popIn",
                            duration: 250,
                            easing: "ease-out"
                        },
                        hideAnimation: {
                            type: "popOut",
                            duration: 250,
                            easing: "ease-out"
                        },
                        centered: true,
                        width: 300,
                        height: 200,
                        styleHtmlContent: true,
                        html: "<p>This is a modal, centered and floating panel. hideOnMaskTap is true by default so we can tap anywhere outside the overlay to hide it.</p>",
                        items: [{
                            docked: "top",
                            xtype: "toolbar",
                            title: "Overlay Title"
                        }],
                        scrollable: true
                    })
                }
                this.overlay.show()
            }
        }, {
            text: "Alert",
            handler: function () {
                Ext.Msg.alert("Title", "The quick brown fox jumped over the lazy dog.", Ext.emptyFn)
            }
        }, {
            text: "Prompt",
            handler: function () {
                Ext.Msg.prompt("Welcome!", "What's your first name?", Ext.emptyFn)
            }
        }, {
            text: "Confirm",
            handler: function () {
                Ext.Msg.confirm("Confirmation", "Are you sure you want to do that?", Ext.emptyFn)
            }
        }, {
            text: "Picker",
            handler: function () {
                if (!this.picker) {
                    this.picker = Ext.Viewport.add({
                        xtype: "picker",
                        slots: [{
                            name: "limit_speed",
                            title: "Speed",
                            data: [{
                                text: "50 KB/s",
                                value: 50
                            }, {
                                text: "100 KB/s",
                                value: 100
                            }, {
                                text: "200 KB/s",
                                value: 200
                            }, {
                                text: "300 KB/s",
                                value: 300
                            }]
                        }]
                    })
                }
                this.picker.show()
            }
        }]
    }
});

Ext.define("Kitchensink.view.Video", {
    extend: "Ext.Container",
    requires: ["Ext.Video"],
    config: {
        layout: "fit",
        items: [{
            xtype: "video",
            url: ["../video/BigBuck.m4v", "../video/BigBuck.webm"],
            loop: true,
            posterUrl: "../video/cover.jpg"
        }]
    }
});


Ext.define("Kitchensink.view.Audio", {
    extend: "Ext.Container",
    requires: ["Ext.Audio"],
    config: {
        layout: Ext.os.is.Android ? {
            type: "vbox",
            pack: "center",
            align: "center"
        } : "fit",
        items: Ext.os.is.Android ? [{
            xtype: "audio",
            cls: "myAudio",
            url: "../audio/crash.mp3",
            loop: true,
            enableControls: false
        }, {
            xtype: "button",
            text: "Play audio",
            margin: 20,
            handler: function () {
                var a = this.getParent().down("audio");
                if (a.isPlaying()) {
                    a.pause();
                    this.setText("Play audio")
                } else {
                    a.play();
                    this.setText("Pause audio")
                }
            }
        }] : [{
            xtype: "audio",
            cls: "myAudio",
            url: "../audio/crash.mp3",
            loop: true
        }]
    }
});



Ext.define("Kitchensink.view.Carousel", {
    extend: "Ext.Container",
    requires: ["Ext.carousel.Carousel"],
    config: {
        cls: "cards",
        layout: {
            type: "vbox",
            align: "stretch"
        },
        defaults: {
            flex: 1
        },
        items: [{
            xtype: "carousel",
            items: [{
                html: "<p>Navigate the carousel on this page by swiping left/right.</p>",
                cls: "card card1"
            }, {
                html: "<p>Clicking on either side of the indicators below</p>",
                cls: "card card2"
            }, {
                html: "Card #3",
                cls: "card card3"
            }]
        }, {
            xtype: "carousel",
            ui: "light",
            direction: "vertical",
            items: [{
                html: "<p>Carousels can be vertical and given a <code>ui</code> of &#8216;light&#8217; or &#8216;dark.&#8217;</p>",
                cls: "card card4"
            }, {
                html: "Card #2",
                cls: "card card5"
            }, {
                html: "Card #3",
                cls: "card card6"
            }]
        }]
    }
});


Ext.define("Contact", {
    extend: "Ext.data.Model",
    config: {
        fields: ["firstName", "lastName"]
    }
});
Ext.create("Ext.data.Store", {
    id: "ListStore",
    model: "Contact",
    sorters: "firstName",
    grouper: function (a) {
        return a.get("firstName")[0]
    },
    data: [{
        firstName: "Julio",
        lastName: "Benesh"
    }, {
        firstName: "Julio",
        lastName: "Minich"
    }, {
        firstName: "Tania",
        lastName: "Ricco"
    }, {
        firstName: "Odessa",
        lastName: "Steuck"
    }, {
        firstName: "Nelson",
        lastName: "Raber"
    }, {
        firstName: "Tyrone",
        lastName: "Scannell"
    }, {
        firstName: "Allan",
        lastName: "Disbrow"
    }, {
        firstName: "Cody",
        lastName: "Herrell"
    }, {
        firstName: "Julio",
        lastName: "Burgoyne"
    }, {
        firstName: "Jessie",
        lastName: "Boedeker"
    }, {
        firstName: "Allan",
        lastName: "Leyendecker"
    }, {
        firstName: "Javier",
        lastName: "Lockley"
    }, {
        firstName: "Guy",
        lastName: "Reasor"
    }, {
        firstName: "Jamie",
        lastName: "Brummer"
    }, {
        firstName: "Jessie",
        lastName: "Casa"
    }, {
        firstName: "Marcie",
        lastName: "Ricca"
    }, {
        firstName: "Gay",
        lastName: "Lamoureaux"
    }, {
        firstName: "Althea",
        lastName: "Sturtz"
    }, {
        firstName: "Kenya",
        lastName: "Morocco"
    }, {
        firstName: "Rae",
        lastName: "Pasquariello"
    }, {
        firstName: "Ted",
        lastName: "Abundis"
    }, {
        firstName: "Jessie",
        lastName: "Schacherer"
    }, {
        firstName: "Jamie",
        lastName: "Gleaves"
    }, {
        firstName: "Hillary",
        lastName: "Spiva"
    }, {
        firstName: "Elinor",
        lastName: "Rockefeller"
    }, {
        firstName: "Dona",
        lastName: "Clauss"
    }, {
        firstName: "Ashlee",
        lastName: "Kennerly"
    }, {
        firstName: "Alana",
        lastName: "Wiersma"
    }, {
        firstName: "Kelly",
        lastName: "Holdman"
    }, {
        firstName: "Mathew",
        lastName: "Lofthouse"
    }, {
        firstName: "Dona",
        lastName: "Tatman"
    }, {
        firstName: "Clayton",
        lastName: "Clear"
    }, {
        firstName: "Rosalinda",
        lastName: "Urman"
    }, {
        firstName: "Cody",
        lastName: "Sayler"
    }, {
        firstName: "Odessa",
        lastName: "Averitt"
    }, {
        firstName: "Ted",
        lastName: "Poage"
    }, {
        firstName: "Penelope",
        lastName: "Gayer"
    }, {
        firstName: "Katy",
        lastName: "Bluford"
    }, {
        firstName: "Kelly",
        lastName: "Mchargue"
    }, {
        firstName: "Kathrine",
        lastName: "Gustavson"
    }, {
        firstName: "Kelly",
        lastName: "Hartson"
    }, {
        firstName: "Carlene",
        lastName: "Summitt"
    }, {
        firstName: "Kathrine",
        lastName: "Vrabel"
    }, {
        firstName: "Roxie",
        lastName: "Mcconn"
    }, {
        firstName: "Margery",
        lastName: "Pullman"
    }, {
        firstName: "Avis",
        lastName: "Bueche"
    }, {
        firstName: "Esmeralda",
        lastName: "Katzer"
    }, {
        firstName: "Tania",
        lastName: "Belmonte"
    }, {
        firstName: "Malinda",
        lastName: "Kwak"
    }, {
        firstName: "Tanisha",
        lastName: "Jobin"
    }, {
        firstName: "Kelly",
        lastName: "Dziedzic"
    }, {
        firstName: "Darren",
        lastName: "Devalle"
    }, {
        firstName: "Julio",
        lastName: "Buchannon"
    }, {
        firstName: "Darren",
        lastName: "Schreier"
    }, {
        firstName: "Jamie",
        lastName: "Pollman"
    }, {
        firstName: "Karina",
        lastName: "Pompey"
    }, {
        firstName: "Hugh",
        lastName: "Snover"
    }, {
        firstName: "Zebra",
        lastName: "Evilias"
    }]
});
Ext.define("Kitchensink.view.List", {
    extend: "Ext.tab.Panel",
    config: {
        activeItem: 2,
        tabBar: {
            docked: "top",
            layout: {
                pack: "center"
            }
        },
        items: [{
            title: "Simple",
            layout: Ext.os.deviceType == "Phone" ? "fit" : {
                type: "vbox",
                align: "center",
                pack: "center"
            },
            cls: "demo-list",
            items: [{
                width: Ext.os.deviceType == "Phone" ? null : 300,
                height: Ext.os.deviceType == "Phone" ? null : 500,
                xtype: "list",
                store: "ListStore",
                itemTpl: '<div class="contact"><strong>{firstName}</strong> {lastName}</div>'
            }]
        }, {
            title: "Grouped",
            layout: Ext.os.deviceType == "Phone" ? "fit" : {
                type: "vbox",
                align: "center",
                pack: "center"
            },
            cls: "demo-list",
            items: [{
                width: Ext.os.deviceType == "Phone" ? null : 300,
                height: Ext.os.deviceType == "Phone" ? null : 500,
                xtype: "list",
                store: "ListStore",
                itemTpl: '<div class="contact"><strong>{firstName}</strong> {lastName}</div>',
                grouped: true,
                indexBar: true
            }]
        }, {
            title: "Disclosure",
            layout: Ext.os.deviceType == "Phone" ? "fit" : {
                type: "vbox",
                align: "center",
                pack: "center"
            },
            cls: "demo-list",
            items: [{
                width: Ext.os.deviceType == "Phone" ? null : 300,
                height: Ext.os.deviceType == "Phone" ? null : 500,
                xtype: "list",
                onItemDisclosure: function (a, c, b) {
                    Ext.Msg.alert("Tap", "Disclose more info for " + a.get("firstName"), Ext.emptyFn)
                },
                store: "ListStore",
                itemTpl: '<div class="contact"><strong>{firstName}</strong> {lastName}</div>'
            }]
        }]
    }
});
Ext.define("Kitchensink.view.Icons", {
    extend: "Ext.tab.Panel",
    config: {
        activeTab: 0,
        layout: {
            animation: {
                type: "slide",
                duration: 250
            }
        },
        tabBar: {
            layout: {
                pack: "center",
                align: "center"
            },
            docked: "bottom",
            scrollable: false
        },
        items: [{
            iconCls: "bookmarks",
            title: "Bookmarks",
            cls: "card card3",
            html: "Both toolbars and tabbars have built-in, ready to use icons. Styling custom icons is no hassle.<p><small>If you can&#8217;t see all of the buttons below, try scrolling left/right.</small></p>"
        }, {
            iconCls: "download",
            title: "Download",
            cls: "card card3",
            html: "Pressed Download"
        }, {
            iconCls: "favorites",
            title: "Favorites",
            cls: "card card3",
            html: "Pressed Favorites"
        }, {
            iconCls: "info",
            title: "Info",
            cls: "card card3",
            html: "Pressed Info"
        }, {
            iconCls: "more",
            title: "More",
            cls: "card card3",
            html: "Pressed More"
        }, {
            xtype: "toolbar",
            ui: "light",
            docked: "top",
            scrollable: false,
            defaults: {
                iconMask: true,
                ui: "plain"
            },
            items: [{
                iconCls: "action"
            }, {
                iconCls: "add"
            }, {
                iconCls: "compose"
            }, {
                iconCls: "delete"
            }, {
                iconCls: "refresh"
            }, {
                iconCls: "reply"
            }],
            layout: {
                pack: "center",
                align: "center"
            }
        }]
    }
});
Ext.define("Kitchensink.view.BottomTabs", {
    extend: "Ext.tab.Panel",
    config: {
        activeTab: 0,
        layout: {
            animation: {
                type: "slide",
                duration: 250
            }
        },
        tabBar: {
            layout: {
                pack: "center",
                align: "center"
            },
            docked: "bottom",
            scrollable: {
                direction: "horizontal",
                indicators: false
            }
        },
        items: [{
            title: "About",
            html: '<p>Docking tabs to the bottom will automatically change their style. The tabs below are ui="light", though the standard type is dark. Badges (like the 4 below) can be added by setting <code>badgeText</code> when creating a tab/card or by using <code>setBadge()</code> on the tab later.</p>',
            iconCls: "info",
            cls: "card card1"
        }, {
            title: "Favorites",
            html: "Favorites Card",
            iconCls: "favorites",
            cls: "card card2",
            badgeText: "4"
        }, {
            title: "Downloads",
            id: "tab3",
            html: "Downloads Card",
            badgeText: "Text can go here too, but it will be cut off if it is too long.",
            cls: "card card3",
            iconCls: "download"
        }, {
            title: "Settings",
            html: "Settings Card",
            cls: "card card4",
            iconCls: "settings"
        }, {
            title: "User",
            html: "User Card",
            cls: "card card5",
            iconCls: "user"
        }]
    }
});



Ext.define("Kitchensink.view.Tabs", {
    extend: "Ext.tab.Panel",
    config: {
        ui: "dark",
        tabBarPosition: "top",
        activeTab: 1,
        items: [{
            title: "Tab 1",
            html: "Tab 1",
            cls: "card card5"
        }, {
            title: "Tab 2",
            html: "Tab 2",
            cls: "card card4"
        }, {
            title: "Tab 3",
            html: "Tab 3",
            cls: "card card3"
        }]
    }
});

Ext.define("Kitchensink.view.Forms", {
    extend: "Ext.tab.Panel",
    requires: ["Ext.form.Panel", "Ext.form.FieldSet", "Ext.field.Number", "Ext.field.Spinner", "Ext.field.Password", "Ext.field.Email", "Ext.field.Url", "Ext.field.DatePicker", "Ext.field.Select", "Ext.field.Hidden", "Ext.field.Radio", "Ext.field.Slider", "Ext.field.Toggle", "Ext.field.Search"],
    config: {
        activeItem: 0,
        tabBarPosition: "top",
        tabBar: {
            layout: {
                pack: "left"
            }
        },
        items: [{
            title: "Basic",
            xtype: "formpanel",
            id: "basicform",
            items: [{
                xtype: "fieldset",
                title: "Personal Info",
                instructions: "Please enter the information above.",
                defaults: {
                    labelWidth: "35%"
                },
                items: [{
                    xtype: "textfield",
                    name: "name",
                    label: "Name",
                    placeHolder: "Tom Roy",
                    autoCapitalize: true,
                    required: true,
                    clearIcon: true
                }, {
                    xtype: "passwordfield",
                    name: "password",
                    label: "Password"
                }, {
                    xtype: "emailfield",
                    name: "email",
                    label: "Email",
                    placeHolder: "me@sencha.com",
                    clearIcon: true
                }, {
                    xtype: "urlfield",
                    name: "url",
                    label: "Url",
                    placeHolder: "http://sencha.com",
                    clearIcon: true
                }, {
                    xtype: "spinnerfield",
                    name: "spinner",
                    label: "Spinner",
                    minValue: 0,
                    maxValue: 10,
                    increment: 1,
                    cycle: true
                }, {
                    xtype: "checkboxfield",
                    name: "cool",
                    label: "Cool"
                }, {
                    xtype: "datepickerfield",
                    name: "date",
                    label: "Start Date",
                    value: new Date(),
                    picker: {
                        yearFrom: 1990
                    }
                }, {
                    xtype: "selectfield",
                    name: "rank",
                    label: "Rank",
                    options: [{
                        text: "Master",
                        value: "master"
                    }, {
                        text: "Journeyman",
                        value: "journeyman"
                    }, {
                        text: "Apprentice",
                        value: "apprentice"
                    }]
                }, {
                    xtype: "textareafield",
                    name: "bio",
                    label: "Bio"
                }]
            }, {
                xtype: "fieldset",
                title: "Favorite color",
                defaults: {
                    xtype: "radiofield",
                    labelWidth: "35%"
                },
                items: [{
                    name: "color",
                    value: "red",
                    label: "Red"
                }, {
                    name: "color",
                    label: "Blue",
                    value: "blue"
                }, {
                    name: "color",
                    label: "Green",
                    value: "green"
                }, {
                    name: "color",
                    label: "Purple",
                    value: "purple"
                }]
            }, {
                xtype: "panel",
                defaults: {
                    xtype: "button",
                    style: "margin: 0.1em",
                    flex: 1
                },
                layout: {
                    type: "hbox"
                },
                items: [{
                    text: "Disable fields",
                    scope: this,
                    hasDisabled: false,
                    handler: function (a) {
                        var b = Ext.getCmp("basicform");
                        if (a.hasDisabled) {
                            b.enable();
                            a.hasDisabled = false;
                            a.setText("Disable fields")
                        } else {
                            b.disable();
                            a.hasDisabled = true;
                            a.setText("Enable fields")
                        }
                    }
                }, {
                    text: "Reset",
                    handler: function () {
                        Ext.getCmp("basicform").reset()
                    }
                }]
            }]
        }, {
            title: "Sliders",
            xtype: "formpanel",
            items: [{
                xtype: "fieldset",
                defaults: {
                    labelWidth: "35%",
                    labelAlign: "top"
                },
                items: [{
                    xtype: "sliderfield",
                    name: "thumb",
                    label: "Single Thumb"
                }, {
                    xtype: "sliderfield",
                    name: "multithumb",
                    label: "Multiple Thumbs",
                    values: [10, 70]
                }, {
                    xtype: "togglefield",
                    name: "toggle",
                    label: "Toggle"
                }]
            }]
        }, {
            title: "Toolbars",
            xtype: "panel",
            items: [{
                styleHtmlContent: true
            }, {
                docked: "top",
                xtype: "toolbar",
                items: [{
                    xtype: "searchfield",
                    placeHolder: "Search",
                    name: "searchfield"
                }]
            }, {
                docked: "top",
                xtype: "toolbar",
                items: [{
                    xtype: "textfield",
                    placeHolder: "Text",
                    name: "searchfield"
                }]
            }, {
                docked: "top",
                xtype: "toolbar",
                items: [{
                    xtype: "selectfield",
                    name: "options",
                    options: [{
                        text: "This is just a big select with a super long option",
                        value: "1"
                    }, {
                        text: "Another select item",
                        value: "2"
                    }]
                }]
            }]
        }]
    }
});
Ext.require("Kitchensink.model.OrderItem", function () {
    Ext.define("Kitchensink.model.Order", {
        extend: "Ext.data.Model",
        config: {
            fields: ["id", "status"],
            hasMany: {
                model: "Kitchensink.model.OrderItem",
                name: "orderItems"
            }
        }
    })
});
Ext.require("Kitchensink.model.Order", function () {
    Ext.define("Kitchensink.model.User", {
        extend: "Ext.data.Model",
        config: {
            fields: ["id", "name"],
            hasMany: {
                model: "Kitchensink.model.Order",
                name: "orders"
            },
            proxy: {
                type: "ajax",
                url: "userData.json"
            }
        }
    })
});

Ext.require(["Ext.data.Store", "Kitchensink.model.User"], function () {
    Ext.define("Kitchensink.view.NestedLoading", {
        extend: "Ext.Container",
        config: {
            layout: "fit",
            items: [{
                docked: "top",
                xtype: "toolbar",
                items: [{
                    text: "Load Nested Data",
                    handler: function () {
                        Ext.getCmp("NestedLoadingDataView").getStore().load()
                    }
                }, {
                    text: "Explain",
                    handler: function () {
                        if (!this.explanation) {
                            this.explanation = Ext.create("Ext.Panel", {
                                modal: true,
                                hideOnMaskTap: true,
                                centered: true,
                                width: 300,
                                height: 200,
                                styleHtmlContent: true,
                                scrollable: true,
                                items: {
                                    docked: "top",
                                    xtype: "toolbar",
                                    title: "Loading Nested Data"
                                },
                                html: ["<p>The data package can load deeply nested data in a single request. In this example we are loading a fictional", "dataset containing Users, their Orders, and each Order's OrderItems.</p>", "<p>Instead of pulling down each record in turn, we load the full data set in a single request and allow the data", "package to automatically parse the nested data.</p>", '<p>As one of the more complex examples, it is worth tapping the "Source" button to see how this is set up.</p>'].join("")
                            });
                            Ext.Viewport.add(this.explanation)
                        }
                        this.explanation.show()
                    }
                }]
            }, {
                xtype: "dataview",
                id: "NestedLoadingDataView",
                emptyText: "No Data Loaded",
                styleHtmlContent: true,
                itemTpl: ['<div class="user">', "<h3>{name}'s orders:</h3>", '<tpl for="orders">', '<div class="order" style="padding: 0 0 10px 20px;">', "Order: {id} ({status})", "<ul>", '<tpl for="orderItems">', "<li>{quantity} x {name}</li>", "</tpl>", "</ul>", "</div>", "</tpl>", "</div>"].join(""),
                store: new Ext.data.Store({
                    model: "Kitchensink.model.User",
                    autoLoad: false
                })
            }]
        }
    })
});