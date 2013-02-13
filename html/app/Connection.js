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
        "click .save": "save",
        "click .edit": "edit",
        "click .remove": "remove",
        "click .cancel": "renderList",
        "click .connect": "connect"
    },

    templates: {
        list: null,
        form: null
    },

    active: null,

    getActive: function() {
        if (!this.active) {
            return null;
        }

        return this.collection.get(this.active);
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

        var index = $(e.target).parents('li').index();
        var connection = this.collection.at(index);
        connection.destroy();

        this.collection.remove(connection);
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

        this.active = connection.id;

        this.renderList();
    },

    save: function() {
        var attributes = serialize(this.$('form'));
        var id = attributes.id;
        delete attributes.id;

        if (!id) {
            this.collection.create(attributes);
            this.active = this.collection.last().id;
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
        var html = this.templates.list({
            connections: this.collection,
            active: this.active
        });
        this.$('.inner').html(html);
    }
});