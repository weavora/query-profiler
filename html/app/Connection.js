var Connection = Backbone.Model.extend({
    defaults: {
        host: "localhost",
        database: "",
        user: "",
        password: "",
        isActive: false
    },

    connect: function() {
        this.collection.resetActive();
        this.set('isActive', true);
        this.save();

        this.trigger('connect', this);
    },

    title: function() {
        return this.get('host') + '/' + this.get('database');
    }
});

var Connections = Backbone.Collection.extend({
    localStorage: new Backbone.LocalStorage("connections"),
    model: Connection,

    active: function() {
        var active = this.find(function(connection) {
            return connection.get('isActive');
        });

        if (!active) {
            active = new Connection();
            active.collection = this;
        }
        return active;
    },

    resetActive: function() {
        this.each(function(connection) {
            if (connection.get('isActive')) {
                connection.set('isActive', false);
                connection.save();
            }
        });
    }
});

var ConnectionsView = Backbone.View.extend({

    events: {
        "click .add": "add",
//        "click .save": "save",
        "click .edit": "edit",
        "click .remove": "remove",
        "click .cancel": "renderList",
        "click .connect": "connect",
        "submit form": "save"
    },

    templates: {
        list: null,
        form: null
    },

    getActive: function() {
        return this.collection.active();
    },

    add: function(e) {
        e.preventDefault();
        this.renderForm(new Connection());
    },

    edit: function(e) {
        e.preventDefault();

        var index = $(e.target).parents('li').index();
        var connection = this.collection.at(index);

        this.renderForm(connection);
    },

    remove: function(e) {
        e.preventDefault();

        if (confirm('Are you sure want to delete this connection?')) {
            var index = $(e.target).parents('li').index();
            var connection = this.collection.at(index);
            connection.destroy();

            this.collection.remove(connection);
        }
    },

    initialize: function(options) {

        this.templates.list = _.template($('#connections_list').html());
        this.templates.form = _.template($('#connection_form').html());

        this.listenTo(this.collection, "change", this.renderList);
        this.listenTo(this.collection, "remove", this.renderList);
        this.listenTo(this.collection, "reset", this.renderList);

        this.collection.fetch();
    },

    connect: function(e) {
        e.preventDefault();

        var index = $(e.target).parents('li').index();
        var connection = this.collection.at(index);

        connection.connect();
    },

    save: function(e) {
        e.preventDefault();

        var attributes = serialize(this.$('form'));
        var id = attributes.id;
        delete attributes.id;

        if (!id) {
            this.collection.create(attributes);
            this.collection.last().connect();
        } else {
            this.collection.get(id).save(attributes);
        }

    },

    renderForm: function(connection) {
        var html = this.templates.form({
            connection: connection
        });
        this.$('.inner').html(html);
    },

    renderList: function() {
        if (this.collection.length == 0) {
            this.renderForm(this.getActive());
            return ;
        }
        var html = this.templates.list({
            connections: this.collection
        });
        this.$('.inner').html(html);
    }
});