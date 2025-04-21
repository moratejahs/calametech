import 'dart:convert';

import 'package:calamitech/core/utils/services/secure_storage_service.dart';
import 'package:calamitech/features/tips/models/tip_model.dart';

class TipsService {
  final SecureStorageService storage;

  TipsService({required this.storage});

  Future<List<TipModel>> get() async {
    final tipsString = await storage.readValue('tips_list');

    if (tipsString == null) return [];

    final List<dynamic> tipsList = jsonDecode(tipsString);

    return tipsList.map((json) => TipModel.fromJson(json)).toList();
  }

  Future<bool> store(List<TipModel> tipsList) async {
    final tipsListJson = tipsList.map((tip) => tip.toJson()).toList();
    final jsonString = jsonEncode(tipsListJson);
    return await storage.writeValue('tips_list', jsonString);
  }

  Future<bool> delete() async {
    return await storage.deleteValue('tips_list');
  }
}
