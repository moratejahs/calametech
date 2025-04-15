import 'package:calamitech/config/theme/app_theme.dart';
import 'package:calamitech/constants/asset_paths.dart';
import 'package:calamitech/constants/route_constants.dart';
import 'package:flutter/material.dart';
import 'package:calamitech/features/home/home.dart';

class CalamityTips extends StatelessWidget {
  const CalamityTips({super.key});

  @override
  Widget build(BuildContext context) {
    return GridView.count(
      crossAxisCount: 2,
      shrinkWrap: true,
      childAspectRatio: 2.0,
      children: [
        Tip(color: AppTheme.primaryColor, image: AssetPaths.fire, title: 'Fire Tips', route: RouteConstants.fireTips),
        Tip(color: Colors.green[400], image: AssetPaths.home, title: 'Flood Tips', route: RouteConstants.floodTips),
        Tip(color: AppTheme.primaryColor, image: AssetPaths.safety, title: 'Safety Tips', route: RouteConstants.safetyTips),
        const Tip(color: Colors.blueGrey, image: AssetPaths.more, title: 'More Tips', route: RouteConstants.tips),
      ],
    );
  }
}
