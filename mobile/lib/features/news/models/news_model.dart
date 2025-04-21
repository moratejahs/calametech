import 'dart:convert';

class NewsModel {
  final String imagePath;
  final String title;
  final String description;
  final String? url;

  NewsModel({
    required this.imagePath,
    required this.title,
    required this.description,
    this.url,
  });

  Map<String, dynamic> toMap() {
    return <String, dynamic>{
      'imagePath': imagePath,
      'title': title,
      'description': description,
      'url': url,
    };
  }

  factory NewsModel.fromMap(Map<String, dynamic> map) {
    return NewsModel(
      imagePath: map['image_path'] as String,
      title: map['title'] as String,
      description: map['description'] as String,
      url: map['url'] as String?,
    );
  }

  String toJson() => json.encode(toMap());

  factory NewsModel.fromJson(String source) => NewsModel.fromMap(json.decode(source) as Map<String, dynamic>);
}
