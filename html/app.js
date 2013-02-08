var Connection = Backbone.Model.extend({
    defaults: {
        host: "localhost",
        database: "",
        user: "",
        password: ""
    },

    title: function() {
        return this.get('host') + '/' + this.get('database');
    }
});

var Connections = Backbone.Collection.extend({
    localStorage: new Backbone.LocalStorage("connections"),
    model: Connection
});

var ConnectionsView = Backbone.View.extend({


    events: {
        "click .add": "add",
        "click li a": "connect"
    },

    templates: {
        list: null
    },

    add: function() {

    },

    initialize: function(options) {

        this.templates.list = _.template($('#connections_list').html());

        this.listenTo(this.collection, "change", this.render);
        this.listenTo(this.collection, "fetch", this.render);

        var view = this;
        this.collection.fetch({
            success: function() {
                view.render();
            }
        });

    },

    connect: function() {

    },

    render: function() {
        var html = this.templates.list({
            connections: this.collection
        });
        this.$el.replaceWith(html);
    }
});

var Query = Backbone.Model.extend({
    defaults: {
        query: "",
        updatedAt: "",
        isFavorite: false,
        profiles: []
    }
});

var Queries = Backbone.Collection.extend({
    localStorage: new Backbone.LocalStorage("queries"),
    model: Query
});

var Profiler = Backbone.Router.extend({
    routes: {
        "dashboard": "dashboard",
        "connection/add": "addConnection"
    },

    initialize: function(options) {
        this.start();

        var connectionsView = new ConnectionsView({
            el: $('.connections-list'),
            collection: new Connections()
        });



//        var connections = new Connections();

//        connections2.reset([
//                    {host: "test1", database: "db1", user: "dev", password: "dev"},
//                    {host: "test2", database: "zerebral", user: "dev", password: "dev"},
//                    {host: "test3", database: "sems", user: "dev", password: "dev"},
//                    {host: "test4", database: "ttm", user: "dev", password: "dev"}
//                ]);
//
//        connections2.create({host: "test1", database: "db1", user: "dev", password: "dev"});

//        console.log(this);
//        connections.fetch();
//        console.log(connections2.models);
//        connections2.sync();
    },

    start: function() {
        console.log('start');
    },

    dashboard: function() {
        console.log('dashboard');
    },

    addConnection: function() {
        console.log('add connection');
    }
});


$(function() {
    var app = new Profiler();
    Backbone.history.start();
});