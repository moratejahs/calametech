import 'package:calamitech/features/tips/models/tip_model.dart';

abstract class ITipsRepository {
  /// If [description] is provided, the implementation should generate tips
  /// specifically based on the description (do not rely on stored tips).
  Future<List<TipModel>> getTips([String? description]);
  Future<List<TipModel>> getStoredTips();
}
