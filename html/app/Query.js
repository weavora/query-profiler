var Query = Backbone.Model.extend({
    defaults: {
        query: "",
        name: "",
        updatedAt: "",
        isFavorite: false,
        isCurrent: false,
        profiles: []
    },

    select: function() {
        this.collection.resetCurrent();
        this.set('isCurrent', true);
        this.save();

        this.trigger('select', this);
    },

    title: function() {
        var name = this.get('name');
        if (name) {
            return name;
        }
        return this.cid.replace('c', 'query #');
    },

    name: function() {
        var name = this.get('name');
        if (name) {
            return name;
        }
        return this.cid.replace('c', 'query #');
    }
});

var Queries = Backbone.Collection.extend({
    localStorage: new Backbone.LocalStorage("queries"),
    model: Query,

    recent: function() {
        var queries = this.filter(function(query) {
            return !query.get('isFavorite');
        });
        queries = _.last(queries, 10);
        return queries.reverse();
    },

    favorite: function() {
        var queries = this.filter(function(query) {
            return query.get('isFavorite');
        });
        return queries.reverse();
    },

    current: function() {
        var current = this.find(function(query) {
            return query.get('isCurrent');
        });

        if (!current) {
            current = this.createNew();
        }
        return current;
    },

    createNew: function() {
        var query = new Query();
        query.collection = this;
        query.set('name', 'query #' + this.length);
        return query;
    },

    resetCurrent: function() {
        this.each(function(query) {
            if (query.get('isCurrent')) {
                query.set('isCurrent', false);
                query.save();
            }
        });
    },

    findBySQL: function(sql) {
        var hash = function(sql) {
            return sql.replace(/\s/g,'').toLowerCase();
        }

        var queryHash = hash(sql);

        return this.find(function(query) {
            return hash(query.get('query')) == queryHash;
        });
    }
});

var QueriesView = Backbone.View.extend({

    events: {
        "click .profile": "profile"
    },

    templates: {
        list: null
    },

    active: null,

    initialize: function(options) {

        this.templates.list = _.template($('#queries_list').html());

        this.listenTo(this.collection, "change", this.render);
        this.listenTo(this.collection, "remove", this.render);
        this.listenTo(this.collection, "reset", this.render);
    },

    profile: function(e) {
        e.preventDefault();

        var id = $(e.target).parents('li').data('id');
        console.log(id);

        this.collection.get(id).select();
    },

    queries: function() {
        return [];
    },

    render: function() {
        var html = this.templates.list({
            queries: this.queries(),
            active: this.active
        });
        this.$('.inner').html(html);
    }
});

var FavoriteQueriesView = QueriesView.extend({
    queries: function() {
        return this.collection.favorite();
    }
});

var RecentQueriesView = QueriesView.extend({
    queries: function() {
        return this.collection.recent();
    }
});