import 'dart:convert';

class SosModel {
  final int? id;
  final String lat;
  final String long;
  final String type;
  final String status;

  SosModel({
    this.id,
    required this.lat,
    required this.long,
    required this.type,
    required this.status,
  });

  SosModel copyWith({
    int? id,
    String? lat,
    String? long,
    String? type,
    String? status,
  }) {
    return SosModel(
      id: id ?? this.id,
      lat: lat ?? this.lat,
      long: long ?? this.long,
      type: type ?? this.type,
      status: status ?? this.status,
    );
  }

  Map<String, dynamic> toMap() {
    return <String, dynamic>{
      'id': id,
      'lat': lat,
      'long': long,
      'type': type,
      'status': status,
    };
  }

  factory SosModel.fromMap(Map<String, dynamic> map) {
    return SosModel(
      id: map['id'] != null ? map['id'] as int : null,
      lat: map['lat'] as String,
      long: map['long'] as String,
      type: map['type'] as String,
      status: map['status'] as String,
    );
  }

  String toJson() => json.encode(toMap());

  factory SosModel.fromJson(String source) =>
      SosModel.fromMap(json.decode(source) as Map<String, dynamic>);

  @override
  String toString() {
    return 'SosModel(id: $id, lat: $lat, long: $long, type: $type, status: $status)';
  }

  @override
  bool operator ==(covariant SosModel other) {
    if (identical(this, other)) return true;

    return other.id == id &&
        other.lat == lat &&
        other.long == long &&
        other.type == type &&
        other.status == status;
  }

  @override
  int get hashCode {
    return id.hashCode ^
        lat.hashCode ^
        long.hashCode ^
        type.hashCode ^
        status.hashCode;
  }
}
