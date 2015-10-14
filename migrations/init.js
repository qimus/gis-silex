db.getCollection('firms').ensureIndex({id: 1});
db.getCollection('firms').ensureIndex({rubrics: 1});
db.getCollection('firms').ensureIndex({building_id: 1});

db.getCollection('buildings').ensureIndex({coords: "2d"});
db.getCollection('firms').ensureIndex({coords: "2d"});

db.getCollection('rubrics').ensureIndex({id: 1});
db.getCollection('rubrics').ensureIndex({path: 1});


//buildings
[
    {
        "id" : 1,
        "address" : "Красный проспект, 36",
        "coords" : [
            55.0302110000000013,
            82.9243539999999939
        ]
    },

    {
        "id" : 2,
        "address" : "Крылова, 15",
        "coords" : [
            55.0411550000000034,
            82.9268220000000014
        ]
    }
].forEach(function(building) {
        db.getCollection('buildings').save(building);
    }
);

//firms
[
    {
        "id" : 1,
        "title" : "ООО Рога и Копыта",
        "phones" : [
            "89505802713",
            "1155654654"
        ],
        "building_id" : 1,
        "rubrics" : [
            1
        ],
        "coords" : [
            55.0302110000000013,
            82.9243539999999939
        ]
    }
].forEach(function(firm) {
        db.getCollection('firms').save(firm);
    }
);

//rubrics
[
    {
        "_id" : ObjectId("561d10ac11f5e56db97de184"),
        "id" : 1,
        "title" : "Еда",
        "path" : []
    },
    {
        "_id" : ObjectId("561d10e411f5e56db97de185"),
        "id" : 2,
        "title" : "Полуфабрикаты оптом",
        "path" : [
            1
        ]
    },
    {
        "_id" : ObjectId("561d110d11f5e56db97de186"),
        "id" : 3,
        "title" : "Мясная продукция",
        "path" : [
            1
        ]
    },
    {
        "_id" : ObjectId("561d112511f5e56db97de187"),
        "id" : 4,
        "title" : "Колбаса",
        "path" : [
            1,
            3
        ]
    },
    {
        "_id" : ObjectId("561d115211f5e56db97de188"),
        "id" : 5,
        "title" : "Автомобили",
        "path" : []
    },
    {
        "_id" : ObjectId("561d117711f5e56db97de189"),
        "id" : 6,
        "title" : "Грузовые",
        "path" : [
            5
        ]
    },
    {
        "_id" : ObjectId("561d119211f5e56db97de18a"),
        "id" : 7,
        "title" : "Легковые",
        "path" : [
            5
        ]
    },
    {
        "_id" : ObjectId("561d11b611f5e56db97de18b"),
        "id" : 8,
        "title" : "Запчасти",
        "path" : [
            5,
            7
        ]
    },
    {
        "_id" : ObjectId("561d11cc11f5e56db97de18c"),
        "id" : 9,
        "title" : "Шины/диски",
        "path" : [
            5,
            7
        ]
    },
    {
        "_id" : ObjectId("561d131511f5e56db97de18d"),
        "id" : 10,
        "title" : "Спорт",
        "path" : []
    }
].forEach(function(rubric) {
        db.getCollection('rubrics').save(rubric);
    }
);