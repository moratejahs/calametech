import 'dart:convert';

class UserModel {
  final int id;
  final String name;
  final String phone;
  final String address;
  final String email;
  final String token;

  UserModel({
    required this.id,
    required this.name,
    required this.phone,
    required this.address,
    required this.email,
    required this.token,
  });

  UserModel copyWith({
    int? id,
    String? name,
    String? phone,
    String? address,
    String? email,
    String? token,
  }) {
    return UserModel(
      id: id ?? this.id,
      name: name ?? this.name,
      phone: phone ?? this.phone,
      address: address ?? this.address,
      email: email ?? this.email,
      token: token ?? this.token,
    );
  }

  Map<String, dynamic> toMap() {
    return <String, dynamic>{
      'id': id,
      'name': name,
      'phone': phone,
      'address': address,
      'email': email,
      'token': token,
    };
  }

  factory UserModel.fromMap(Map<String, dynamic> map) {
    return UserModel(
      id: map['id'] as int,
      name: map['name'] as String,
      phone: map['phone'] as String,
      address: map['address'] as String,
      email: map['email'] as String,
      token: map['token'] as String,
    );
  }

  String toJson() => json.encode(toMap());

  factory UserModel.fromJson(String source) => UserModel.fromMap(json.decode(source) as Map<String, dynamic>);

  @override
  String toString() {
    return 'UserModel(id: $id, name: $name, phone: $phone, address: $address, email: $email, token: $token)';
  }

  @override
  bool operator ==(covariant UserModel other) {
    if (identical(this, other)) return true;
  
    return 
      other.id == id &&
      other.name == name &&
      other.phone == phone &&
      other.address == address &&
      other.email == email &&
      other.token == token;
  }

  @override
  int get hashCode {
    return id.hashCode ^
      name.hashCode ^
      phone.hashCode ^
      address.hashCode ^
      email.hashCode ^
      token.hashCode;
  }
}
