import 'package:calamitech/config/theme/app_theme.dart';
import 'package:calamitech/features/tips/blocs/tips_bloc.dart';
import 'package:calamitech/features/tips/presentation/tip_card.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

class TipsScreen extends StatefulWidget {
  final String? tipType;

  const TipsScreen({super.key, this.tipType});

  @override
  State<TipsScreen> createState() => _TipsScreenState();
}

class _TipsScreenState extends State<TipsScreen> {
  @override
  void initState() {
    super.initState();
    if (!mounted) return;
    context.read<TipsBloc>().add(TipsFetched());
  }

  final tipTypes = {
    'safety_tips': 'Safety Tips',
    'fire_tips': 'Fire Tips',
    'flood_tips': 'Flood Tips',
  };
  
  String getTitle (String? tipType) {
    return tipTypes[tipType] ?? 'Tips';
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(
          getTitle(widget.tipType),
          style: const TextStyle(
            color: Colors.white,
          ),
        ),
        backgroundColor: AppTheme.primaryColor,
      ),
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: BlocBuilder<TipsBloc, TipsState>(
            builder: (context, state) {
              if (state is TipsLoading) {
                return const Center(
                  child: CircularProgressIndicator(),
                );
              }

              if (state is TipsLoaded) {
                final tips = state.tips;
                final tipsToShow = widget.tipType != null
                    ? tips.where((tip) => tip.type == widget.tipType).toList()
                    : tips.where((tip) => tip.type == 'other_tips').toList();

                return tipsToShow.isEmpty
                    ? const Center(
                        child: Text('No tips available'),
                      )
                    : ListView.builder(
                        scrollDirection: Axis.vertical,
                        itemCount: tipsToShow.length,
                        itemBuilder: (context, index) {
                          return Container(
                            margin: const EdgeInsets.only(bottom: 12),
                            width: double.infinity,
                            child: TipCard(
                                index: index + 1, tip: tipsToShow[index]),
                          );
                        },
                      );
              }

              if (state is TipsFailure) {
                return Center(
                  child: Text(state.message),
                );
              }

              return const SizedBox.shrink();
            },
          ),
        ),
      ),
    );
  }
}
