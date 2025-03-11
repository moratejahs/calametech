import 'dart:convert';

class Tip {
  final String content;
  final String type;
  Tip({
    required this.content,
    required this.type,
  });

  Tip copyWith({
    String? content,
    String? type,
  }) {
    return Tip(
      content: content ?? this.content,
      type: type ?? this.type,
    );
  }

  Map<String, dynamic> toMap() {
    return <String, dynamic>{
      'content': content,
      'type': type,
    };
  }

  factory Tip.fromMap(Map<String, dynamic> map) {
    return Tip(
      content: map['content'] as String,
      type: map['type'] as String,
    );
  }

  String toJson() => json.encode(toMap());

  factory Tip.fromJson(String source) => Tip.fromMap(json.decode(source) as Map<String, dynamic>);

  @override
  String toString() => 'Tip(content: $content, type: $type)';

  @override
  bool operator ==(covariant Tip other) {
    if (identical(this, other)) return true;

    return other.content == content && other.type == type;
  }

  @override
  int get hashCode => content.hashCode ^ type.hashCode;
}
