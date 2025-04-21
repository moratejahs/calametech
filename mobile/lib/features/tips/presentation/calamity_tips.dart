import 'package:flutter/material.dart';
import 'package:calamitech/config/theme/app_theme.dart';
import 'package:calamitech/constants/asset_paths.dart';
import 'package:calamitech/constants/route_constants.dart';
import 'package:calamitech/features/tips/presentation/tip_button.dart';

class CalamityTips extends StatelessWidget {
  const CalamityTips({super.key});

  @override
  Widget build(BuildContext context) {
    return GridView.count(
      crossAxisCount: 2,
      shrinkWrap: true,
      childAspectRatio: 2.0,
      children: [
        TipButton(color: AppTheme.primaryColor, image: AssetPaths.fire, title: 'Fire Tips', route: RouteConstants.fireTips),
        TipButton(color: Colors.green[400], image: AssetPaths.home, title: 'Flood Tips', route: RouteConstants.floodTips),
        TipButton(color: AppTheme.primaryColor, image: AssetPaths.safety, title: 'Safety Tips', route: RouteConstants.safetyTips),
        const TipButton(color: Colors.blueGrey, image: AssetPaths.more, title: 'More Tips', route: RouteConstants.tips),
      ],
    );
  }
}
