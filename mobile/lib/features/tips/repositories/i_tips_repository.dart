import 'package:calamitech/features/tips/models/tip_model.dart';

abstract class ITipsRepository {
  Future<List<TipModel>> getTips();
  Future<List<TipModel>> getStoredTips();
}